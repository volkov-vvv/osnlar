<?php

namespace App\Service;

use App\Models\BioLid;
use App\Models\UserDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use ZipArchive;

class BioLidDocumentsArchiveService
{
    /**
     * Типы и суффиксы имён файлов для выгрузки агента.
     *
     * @var array<string, string>
     */
    private const AGENT_DOC_SUFFIXES = [
        'education_doc' => 'диплом',
        'consent_arpm' => 'СОПД',
        'application' => 'Заявление',
        'name_change_doc' => 'смена фамилии',
    ];

    /**
     * Собирает zip с папками по заявкам и загруженными BIO-документами.
     *
     * @param  list<int|string>  $bioLidIds
     * @return string Абсолютный путь к временному .zip
     */
    public function create(array $bioLidIds): string
    {
        $ids = $this->normalizeIds($bioLidIds);
        $bioTypes = array_keys(UserDocument::allBioDocs());
        $bioLids = $this->loadBioLids($ids, $bioTypes);

        $zipPath = $this->makeTempZipPath();
        $zip = $this->openZip($zipPath);
        $disk = Storage::disk('public');
        $usedFolders = [];

        foreach ($bioLids as $bioLid) {
            $folder = $this->uniqueName(
                $this->personFolderName($bioLid),
                $usedFolders
            );
            $usedFolders[$folder] = true;
            $usedFiles = [];
            $added = 0;

            foreach ($bioLid->documents as $document) {
                $absolute = $this->absoluteDocumentPath($disk, $document);
                if ($absolute === null) {
                    continue;
                }

                $entryName = $folder.'/'.$this->documentFileName($document, $usedFiles);
                if (! $zip->addFile($absolute, $entryName)) {
                    continue;
                }
                $added++;
            }

            if ($added === 0) {
                $zip->addEmptyDir($folder);
            }
        }

        $zip->close();

        return $zipPath;
    }

    /**
     * Архив для агента: плоский список файлов (диплом, СОПД АРПМ, заявление, смена фамилии).
     *
     * @param  list<int|string>  $bioLidIds
     * @return array{path: string, filename: string}
     */
    public function createForAgent(array $bioLidIds, ?string $studyGroupTitle = null): array
    {
        $ids = $this->normalizeIds($bioLidIds);
        $types = array_keys(self::AGENT_DOC_SUFFIXES);
        $bioLids = $this->loadBioLids($ids, $types, withStudyGroups: true);

        $zipPath = $this->makeTempZipPath();
        $zip = $this->openZip($zipPath);
        $disk = Storage::disk('public');
        $usedFiles = [];
        $added = 0;

        foreach ($bioLids as $bioLid) {
            $docsByType = $bioLid->documents
                ->sortByDesc('id')
                ->unique('type')
                ->keyBy('type');

            foreach (self::AGENT_DOC_SUFFIXES as $type => $suffix) {
                $document = $docsByType->get($type);
                if (! $document) {
                    continue;
                }

                $absolute = $this->absoluteDocumentPath($disk, $document);
                if ($absolute === null) {
                    continue;
                }

                $entryName = $this->agentDocumentFileName($bioLid, $document, $suffix, $usedFiles);
                if (! $zip->addFile($absolute, $entryName)) {
                    continue;
                }
                $added++;
            }
        }

        if ($added === 0) {
            $zip->close();
            @unlink($zipPath);

            throw new RuntimeException(
                'Нет файлов для архива: нужны диплом, СОПД АРПМ, заявление (и смена фамилии при наличии)'
            );
        }

        if ($zip->close() !== true || ! is_file($zipPath)) {
            @unlink($zipPath);
            throw new RuntimeException('Не удалось сохранить архив документов');
        }

        $groupTitle = trim((string) $studyGroupTitle);
        if ($groupTitle === '') {
            $groupTitle = $this->resolveStudyGroupTitle($bioLids);
        }

        $filename = 'dokumenty-bio-'.$this->sanitizeZipName($groupTitle !== '' ? $groupTitle : 'gruppa').'.zip';

        return [
            'path' => $zipPath,
            'filename' => $filename,
        ];
    }

    /**
     * @param  list<int|string>  $bioLidIds
     * @return list<int>
     */
    private function normalizeIds(array $bioLidIds): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $bioLidIds))));
        if ($ids === []) {
            throw new RuntimeException('Не выбраны заявки для архива');
        }

        return $ids;
    }

    /**
     * @param  list<int>  $ids
     * @param  list<string>  $types
     * @return Collection<int, BioLid>
     */
    private function loadBioLids(array $ids, array $types, bool $withStudyGroups = false): Collection
    {
        $query = BioLid::query()
            ->with(['documents' => function ($query) use ($types) {
                $query->whereNotNull('file')
                    ->where('file', '!=', '')
                    ->whereIn('type', $types)
                    ->orderByDesc('id');
            }])
            ->whereIn('id', $ids)
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->orderBy('middlename')
            ->orderBy('id');

        if ($withStudyGroups) {
            $query->with(['user.studyGroups']);
        }

        $bioLids = $query->get();
        if ($bioLids->isEmpty()) {
            throw new RuntimeException('Заявки не найдены');
        }

        return $bioLids;
    }

    private function makeTempZipPath(): string
    {
        $tmpBase = tempnam(sys_get_temp_dir(), 'biolid_docs_');
        if ($tmpBase === false) {
            throw new RuntimeException('Не удалось создать временный файл');
        }

        $zipPath = $tmpBase.'.zip';
        @unlink($tmpBase);

        return $zipPath;
    }

    private function openZip(string $zipPath): ZipArchive
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Не удалось создать архив');
        }

        return $zip;
    }

    private function absoluteDocumentPath($disk, UserDocument $document): ?string
    {
        $relative = (string) $document->file;
        if ($relative === '' || ! $disk->exists($relative)) {
            return null;
        }

        $absolute = $disk->path($relative);

        return is_file($absolute) ? $absolute : null;
    }

    private function personFolderName(BioLid $bioLid): string
    {
        $fio = trim(implode(' ', array_filter([
            $bioLid->lastname,
            $bioLid->firstname,
            $bioLid->middlename,
        ])));

        $base = $this->sanitizeZipName($fio !== '' ? $fio : 'bez-fio');

        return $base.'_'.$bioLid->id;
    }

    private function documentFileName(UserDocument $document, array &$usedFiles): string
    {
        $ext = strtolower((string) pathinfo((string) $document->file, PATHINFO_EXTENSION));
        $meta = UserDocument::allBioDocs()[$document->type] ?? null;
        $title = is_array($meta) ? (string) ($meta['title'] ?? '') : '';
        if ($title === '') {
            $title = (string) ($document->title ?: $document->type);
        }

        $base = $this->sanitizeZipName($title);
        $fileName = $ext !== '' ? $base.'.'.$ext : $base;

        return $this->uniqueName($fileName, $usedFiles, $ext);
    }

    private function agentDocumentFileName(
        BioLid $bioLid,
        UserDocument $document,
        string $suffix,
        array &$usedFiles
    ): string {
        $ext = strtolower((string) pathinfo((string) $document->file, PATHINFO_EXTENSION));
        $base = $this->sanitizeZipName($this->personIoPrefix($bioLid).'_'.$suffix);
        $fileName = $ext !== '' ? $base.'.'.$ext : $base;

        return $this->uniqueName($fileName, $usedFiles, $ext);
    }

    private function personIoPrefix(BioLid $bioLid): string
    {
        $lastname = $this->sanitizeZipName((string) $bioLid->lastname);
        if ($lastname === '' || $lastname === 'bez-imeni') {
            $lastname = 'bez-familii';
        }

        $initials = '';
        $firstname = trim((string) $bioLid->firstname);
        $middlename = trim((string) $bioLid->middlename);

        if ($firstname !== '') {
            $initials .= mb_strtoupper(mb_substr($firstname, 0, 1));
        }
        if ($middlename !== '') {
            $initials .= mb_strtoupper(mb_substr($middlename, 0, 1));
        }

        return $initials !== '' ? $lastname.'_'.$initials : $lastname;
    }

    /**
     * @param  Collection<int, BioLid>  $bioLids
     */
    private function resolveStudyGroupTitle(Collection $bioLids): string
    {
        $titles = $bioLids
            ->flatMap(static function (BioLid $bioLid) {
                return $bioLid->user?->studyGroups ?? [];
            })
            ->map(static function ($studyGroup) {
                return trim((string) ($studyGroup->short_title ?: $studyGroup->title));
            })
            ->filter()
            ->unique()
            ->values();

        if ($titles->count() === 1) {
            return (string) $titles->first();
        }

        return '';
    }

    /**
     * @param  array<string, true>  $used
     */
    private function uniqueName(string $name, array &$used, string $ext = ''): string
    {
        $candidate = $name;
        $i = 2;

        while (isset($used[$candidate])) {
            if ($ext !== '' && str_ends_with(strtolower($name), '.'.$ext)) {
                $stem = substr($name, 0, - (strlen($ext) + 1));
                $candidate = $stem.'_'.$i.'.'.$ext;
            } else {
                $candidate = $name.'_'.$i;
            }
            $i++;
        }

        $used[$candidate] = true;

        return $candidate;
    }

    private function sanitizeZipName(string $name): string
    {
        $name = preg_replace('/[\\\\\/:\*\?"<>|\x00-\x1F]+/u', '_', $name) ?? '';
        $name = preg_replace('/\s+/u', '_', trim($name)) ?? '';
        $name = trim($name, '._');

        return $name !== '' ? $name : 'bez-imeni';
    }
}
