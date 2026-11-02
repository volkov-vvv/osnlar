<?php

namespace App\Service;

use App\Models\BioLid;
use Carbon\CarbonInterface;
use RuntimeException;
use ZipArchive;

class BioLidConsentDocumentService
{
    public const KIND_ARPM = 'arpm';
    public const KIND_OSNOVANIE = 'osnovanie';

    private const TEMPLATES = [
        self::KIND_ARPM => [
            'path' => 'templates/bio/consent_arpm.docx',
            'filename' => 'Soglasie-ARPM.pdf',
        ],
        self::KIND_OSNOVANIE => [
            'path' => 'templates/bio/consent_osnovanie.docx',
            'filename' => 'Soglasie-Osnovanie.pdf',
        ],
    ];

    public function __construct(
        protected DocxToPdfConverter $docxToPdfConverter
    ) {
    }

    /**
     * @return array{binary: string, filename: string}
     */
    public function generate(BioLid $bioLid, string $kind): array
    {
        if (! isset(self::TEMPLATES[$kind])) {
            throw new RuntimeException('Неизвестный тип согласия');
        }

        $payload = $this->buildPayload($bioLid);
        $template = self::TEMPLATES[$kind];

        return [
            'binary' => $this->docxToPdfConverter->convert(
                $this->fillTemplate(resource_path($template['path']), $payload)
            ),
            'filename' => $template['filename'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function buildPayload(BioLid $bioLid): array
    {
        $fio = trim((string) $bioLid->full_name);
        if ($fio === '') {
            throw new RuntimeException('В заявке не указано ФИО');
        }

        $address = trim((string) $bioLid->residence_address);
        if ($address === '') {
            throw new RuntimeException('В заявке не указан адрес места жительства');
        }

        foreach ([
            'passport_series' => 'серия паспорта',
            'passport_number' => 'номер паспорта',
            'passport_issued_by' => 'кем выдан паспорт',
            'passport_department_code' => 'код подразделения',
        ] as $field => $label) {
            if (trim((string) $bioLid->{$field}) === '') {
                throw new RuntimeException('В заявке не указано поле: '.$label);
            }
        }

        if (! $bioLid->passport_issued_at instanceof CarbonInterface) {
            throw new RuntimeException('В заявке не указана дата выдачи паспорта');
        }

        return [
            '{{FIO}}' => $this->escapeXml($fio),
            '{{ADDRESS}}' => $this->escapeXml($address),
            '{{PASSPORT_SERIES}}' => $this->escapeXml((string) $bioLid->passport_series),
            '{{PASSPORT_NUMBER}}' => $this->escapeXml((string) $bioLid->passport_number),
            '{{PASSPORT_ISSUED_BY}}' => $this->escapeXml((string) $bioLid->passport_issued_by),
            '{{PASSPORT_ISSUED_AT}}' => $this->escapeXml($bioLid->passport_issued_at->format('d.m.Y')),
            '{{PASSPORT_DEPARTMENT_CODE}}' => $this->escapeXml((string) $bioLid->passport_department_code),
            '{{DOC_DATE}}' => $this->escapeXml($this->formatDocumentDate(now())),
        ];
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected function fillTemplate(string $templatePath, array $replacements): string
    {
        if (! is_file($templatePath)) {
            throw new RuntimeException('Шаблон согласия не найден');
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'bio_consent_');
        if ($tempPath === false) {
            throw new RuntimeException('Не удалось создать временный файл');
        }

        $workingPath = $tempPath.'.docx';
        if (! @copy($templatePath, $workingPath)) {
            @unlink($tempPath);
            throw new RuntimeException('Не удалось подготовить шаблон согласия');
        }
        @unlink($tempPath);

        $zip = new ZipArchive();
        if ($zip->open($workingPath) !== true) {
            @unlink($workingPath);
            throw new RuntimeException('Не удалось открыть шаблон согласия');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            @unlink($workingPath);
            throw new RuntimeException('Повреждён шаблон согласия');
        }

        $filledXml = strtr($documentXml, $replacements);
        foreach (array_keys($replacements) as $placeholder) {
            if (str_contains($filledXml, $placeholder)) {
                $zip->close();
                @unlink($workingPath);
                throw new RuntimeException('Не удалось подставить данные в шаблон согласия');
            }
        }

        $zip->deleteName('word/document.xml');
        $zip->addFromString('word/document.xml', $filledXml);
        $zip->close();

        $binary = file_get_contents($workingPath);
        @unlink($workingPath);

        if ($binary === false || $binary === '') {
            throw new RuntimeException('Не удалось сформировать файл согласия');
        }

        return $binary;
    }

    protected function formatDocumentDate(CarbonInterface $date): string
    {
        $months = [
            1 => 'января',
            2 => 'февраля',
            3 => 'марта',
            4 => 'апреля',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'августа',
            9 => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря',
        ];

        return '«'.$date->format('d').'» '.$months[(int) $date->format('n')].' '.$date->format('Y').' г.';
    }

    protected function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
