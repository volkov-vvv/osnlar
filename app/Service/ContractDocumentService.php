<?php

namespace App\Service;

use App\Models\Contract;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class ContractDocumentService
{
    public const TEMPLATE_RELATIVE_PATH = 'templates/contracts/author_agreement.docx';

    /**
     * @return array{binary: string, filename: string}
     */
    public function generate(Contract $contract): array
    {
        $contract->loadMissing(['author', 'course', 'project']);

        $author = $contract->author;
        $course = $contract->course;
        $project = $contract->project;

        $authorName = $author->full_name ?: ($author->name ?? '—');
        $authorPseudonym = $author->name ?: $authorName;
        $projectPhrase = trim((string) ($project?->contract_phrase ?? ''));
        $projectPhrase = $projectPhrase !== '' ? ' '.$projectPhrase : '';

        $replacements = [
            '{{CONTRACT_NUMBER}}' => $this->escapeXml((string) ($contract->number ?: '________')),
            '{{CONTRACT_DATE}}' => $this->escapeXml($contract->signedAtLabel()),
            '{{AUTHOR_NAME}}' => $this->escapeXml($authorName),
            '{{AUTHOR_PSEUDONYM}}' => $this->escapeXml($authorPseudonym),
            '{{AUTHOR_NAME_GENITIVE}}' => $this->escapeXml($author->genitiveName()),
            '{{NAP_DATE}}' => $this->escapeXml($contract->napRegisteredAtLabel()),
            '{{PROGRAM_PHRASE}}' => $this->escapeXml($course?->programTypePhrase() ?? 'программы'),
            '{{PROGRAM_DATIVE}}' => $this->escapeXml(match ($course->type ?? null) {
                'professional_retraining' => 'дополнительной профессиональной программе профессиональной переподготовки',
                'advanced_training' => 'дополнительной профессиональной программе повышения квалификации',
                'professional_education' => 'программе профессионального обучения',
                default => 'программе',
            }),
            '{{COURSE_TITLE}}' => $this->escapeXml($course->title ?? '—'),
            '{{COURSE_HOURS}}' => $this->escapeXml($course?->studyHoursFullLabel() ?? '—'),
            '{{PROJECT_PHRASE}}' => $this->escapeXml($projectPhrase),
            '{{ENDS_AT}}' => $this->escapeXml($contract->endsAtLabel()),
            '{{PASSPORT_SERIES}}' => $this->escapeXml($author->passport_series ?: '____'),
            '{{PASSPORT_NUMBER}}' => $this->escapeXml($author->passport_number ?: '_____'),
            '{{PASSPORT_ISSUED_BY}}' => $this->escapeXml($author->passport_issued_by ?: '_________'),
            '{{PASSPORT_ISSUED_AT}}' => $this->escapeXml(
                $author->passport_issued_at ? $author->passport_issued_at->format('d.m.Y') : '______'
            ),
            '{{PASSPORT_DEPT}}' => $this->escapeXml($author->passport_department_code ?: '______'),
            '{{INN}}' => $this->escapeXml($author->inn ?: '______'),
            '{{REGISTRATION_ADDRESS}}' => $this->escapeXml($author->registration_address ?: '______'),
            '{{PHONE}}' => $this->escapeXml($author->phone ?: '—'),
            '{{EMAIL}}' => $this->escapeXml($author->email ?: '—'),
            '{{BANK_ACCOUNT}}' => $this->escapeXml($author->bank_account ?: '______'),
            '{{BANK_NAME}}' => $this->escapeXml($author->bank_name ?: '________'),
            '{{BANK_BIK}}' => $this->escapeXml($author->bank_bik ?: '______'),
            '{{BANK_CORR}}' => $this->escapeXml($author->bank_correspondent_account ?: '_________________'),
        ];

        $filename = 'dogovor-'.$contract->template.'-'.Str::slug((string) ($contract->number ?: $contract->id));
        $nameSlug = Str::slug($authorName);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        return [
            'binary' => $this->fillTemplate($replacements),
            'filename' => $filename.'.docx',
        ];
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected function fillTemplate(array $replacements): string
    {
        $templatePath = resource_path(self::TEMPLATE_RELATIVE_PATH);
        if (! is_file($templatePath)) {
            throw new RuntimeException('Шаблон договора не найден');
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'contract_');
        if ($tempPath === false) {
            throw new RuntimeException('Не удалось создать временный файл');
        }

        $workingPath = $tempPath.'.docx';
        if (! @copy($templatePath, $workingPath)) {
            @unlink($tempPath);
            throw new RuntimeException('Не удалось подготовить шаблон договора');
        }
        @unlink($tempPath);

        $zip = new ZipArchive();
        if ($zip->open($workingPath) !== true) {
            @unlink($workingPath);
            throw new RuntimeException('Не удалось открыть шаблон договора');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            @unlink($workingPath);
            throw new RuntimeException('Повреждён шаблон договора');
        }

        $filledXml = strtr($documentXml, $replacements);
        foreach (array_keys($replacements) as $placeholder) {
            if (str_contains($filledXml, $placeholder)) {
                $zip->close();
                @unlink($workingPath);
                throw new RuntimeException('Не удалось подставить данные в шаблон договора: '.$placeholder);
            }
        }

        $zip->deleteName('word/document.xml');
        $zip->addFromString('word/document.xml', $filledXml);
        $zip->close();

        $binary = file_get_contents($workingPath);
        @unlink($workingPath);

        if ($binary === false || $binary === '') {
            throw new RuntimeException('Не удалось сформировать файл договора');
        }

        return $binary;
    }

    protected function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
