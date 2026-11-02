<?php

namespace App\Service;

use App\Models\BioLid;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use ZipArchive;

class BioLidDocumentsArchiveService
{
    /**
     * Собирает zip с папками по заявкам и загруженными BIO-документами.
     *
     * @param  list<int|string>  $bioLidIds
     * @return string Абсолютный путь к временному .zip
     */
    public function create(array $bioLidIds): string
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $bioLidIds))));
        if ($ids === []) {
            throw new RuntimeException('Не выбраны заявки для архива');
        }

        $bioTypes = array_keys(UserDocument::allBioDocs());

        $bioLids = BioLid::query()
            ->with(['documents' => function ($query) use ($bioTypes) {
                $query->whereNotNull('file')
                    ->where('file', '!=', '')
                    ->whereIn('type', $bioTypes)
                    ->orderBy('id');
            }])
            ->whereIn('id', $ids)
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->orderBy('middlename')
            ->orderBy('id')
            ->get();

        if ($bioLids->isEmpty()) {
            throw new RuntimeException('Заявки не найдены');
        }

        $tmpBase = tempnam(sys_get_temp_dir(), 'biolid_docs_');
        if ($tmpBase === false) {
            throw new RuntimeException('Не удалось создать временный файл');
        }

        $zipPath = $tmpBase.'.zip';
        @unlink($tmpBase);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Не удалось создать архив');
        }

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
                $relative = (string) $document->file;
                if ($relative === '' || ! $disk->exists($relative)) {
                    continue;
                }

                $absolute = $disk->path($relative);
                if (! is_file($absolute)) {
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
