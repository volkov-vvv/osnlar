<?php

namespace App\Service;

use App\Models\BioLid;
use App\Models\Course;
use App\Models\StudyGroup;
use Carbon\CarbonInterface;
use morphos\Russian\Cases;
use RuntimeException;
use ZipArchive;
use function morphos\Russian\inflectName;

class BioLidApplicationDocumentService
{
    public const TEMPLATE_RELATIVE_PATH = 'templates/bio/zayavlenie.docx';

    public function __construct(
        protected DocxToPdfConverter $docxToPdfConverter
    ) {
    }

    /**
     * @return array{binary: string, filename: string}
     */
    public function generate(BioLid $bioLid): array
    {
        $bioLid->loadMissing(['course.biolidStudyGroup']);

        $course = $bioLid->course;
        if (! $course instanceof Course) {
            throw new RuntimeException('У заявки не указан курс');
        }

        $studyGroup = $course->biolidStudyGroup;
        if (! $studyGroup instanceof StudyGroup) {
            throw new RuntimeException('У курса не выбрана учебная группа для заявок');
        }

        if (! $studyGroup->starts_at || ! $studyGroup->ends_at) {
            throw new RuntimeException('У учебной группы не указаны сроки обучения');
        }

        $hours = $course->study_hours;
        if ($hours === null || $hours === '') {
            throw new RuntimeException('У курса не указаны академические часы');
        }

        $title = trim((string) $course->title);
        if ($title === '') {
            throw new RuntimeException('У курса не указано название');
        }

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

        $replacements = [
            '{{COURSE_TITLE}}' => $this->escapeXml($title),
            '{{STUDY_HOURS_PHRASE}}' => $this->escapeXml($this->studyHoursPhrase((int) $hours)),
            '{{PERIOD_DATES}}' => $this->escapeXml(
                $studyGroup->starts_at->format('d.m.Y').' г. по '.$studyGroup->ends_at->format('d.m.Y')
            ),
            '{{FIO}}' => $this->escapeXml($fio),
            '{{FIO_GENITIVE}}' => $this->escapeXml($this->inflectFullNameGenitive($fio, $bioLid->gender)),
            '{{ADDRESS}}' => $this->escapeXml($address),
            '{{CITIZENSHIP}}' => $this->escapeXml(trim((string) $bioLid->citizenship)),
            '{{PASSPORT_SERIES_NUMBER}}' => $this->escapeXml(
                trim((string) $bioLid->passport_series).' '.trim((string) $bioLid->passport_number)
            ),
            '{{PASSPORT_ISSUED_BY}}' => $this->escapeXml(trim((string) $bioLid->passport_issued_by)),
            '{{PASSPORT_ISSUED_AT}}' => $this->escapeXml($bioLid->passport_issued_at->format('d.m.Y')),
            '{{PASSPORT_DEPARTMENT_CODE}}' => $this->escapeXml(trim((string) $bioLid->passport_department_code)),
            '{{BIRTH_DATE}}' => $this->escapeXml(
                $bioLid->birth_date instanceof CarbonInterface
                    ? $bioLid->birth_date->format('d.m.Y')
                    : ''
            ),
            '{{SNILS}}' => $this->escapeXml(trim((string) $bioLid->snils)),
            '{{EDUCATION_ORGANIZATION}}' => $this->escapeXml(trim((string) $bioLid->education_organization)),
            '{{EDUCATION_QUALIFICATION}}' => $this->escapeXml(trim((string) $bioLid->education_qualification)),
            '{{DIPLOMA_SERIES_NUMBER}}' => $this->escapeXml(trim((string) $bioLid->diploma_series_number)),
            '{{DIPLOMA_ISSUED_AT}}' => $this->escapeXml(
                $bioLid->diploma_issued_at instanceof CarbonInterface
                    ? $bioLid->diploma_issued_at->format('d.m.Y')
                    : ''
            ),
            '{{DOC_DATE}}' => $this->escapeXml($this->formatDocumentDate(now())),
        ];

        return [
            'binary' => $this->docxToPdfConverter->convert($this->fillTemplate($replacements)),
            'filename' => 'Zayavlenie.pdf',
        ];
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected function fillTemplate(array $replacements): string
    {
        $templatePath = resource_path(self::TEMPLATE_RELATIVE_PATH);
        if (! is_file($templatePath)) {
            throw new RuntimeException('Шаблон заявления не найден');
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'bio_app_');
        if ($tempPath === false) {
            throw new RuntimeException('Не удалось создать временный файл');
        }

        $workingPath = $tempPath.'.docx';
        if (! @copy($templatePath, $workingPath)) {
            @unlink($tempPath);
            throw new RuntimeException('Не удалось подготовить шаблон заявления');
        }
        @unlink($tempPath);

        $zip = new ZipArchive();
        if ($zip->open($workingPath) !== true) {
            @unlink($workingPath);
            throw new RuntimeException('Не удалось открыть шаблон заявления');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            @unlink($workingPath);
            throw new RuntimeException('Повреждён шаблон заявления');
        }

        $filledXml = strtr($documentXml, $replacements);
        foreach (array_keys($replacements) as $placeholder) {
            if (str_contains($filledXml, $placeholder)) {
                $zip->close();
                @unlink($workingPath);
                throw new RuntimeException('Не удалось подставить данные в шаблон заявления');
            }
        }

        $zip->deleteName('word/document.xml');
        $zip->addFromString('word/document.xml', $filledXml);
        $zip->close();

        $binary = file_get_contents($workingPath);
        @unlink($workingPath);

        if ($binary === false || $binary === '') {
            throw new RuntimeException('Не удалось сформировать файл заявления');
        }

        return $binary;
    }

    protected function studyHoursPhrase(int $hours): string
    {
        $mod100 = $hours % 100;
        $mod10 = $hours % 10;

        if ($mod100 >= 11 && $mod100 <= 14) {
            return $hours.' академических часов';
        }

        if ($mod10 === 1) {
            return $hours.' академический час';
        }

        if ($mod10 >= 2 && $mod10 <= 4) {
            return $hours.' академических часа';
        }

        return $hours.' академических часов';
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

    protected function inflectFullNameGenitive(string $fullName, ?string $gender): string
    {
        $genderHint = match ($gender) {
            'male' => 'm',
            'female' => 'f',
            default => null,
        };

        try {
            $inflected = $genderHint
                ? inflectName($fullName, Cases::RODIT, $genderHint)
                : inflectName($fullName, Cases::RODIT);
        } catch (\Throwable) {
            return $fullName;
        }

        $inflected = trim((string) $inflected);

        return $inflected !== '' ? $inflected : $fullName;
    }

    protected function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
