<?php

namespace App\Service;

use App\Models\ContractReport;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class ContractReportDocumentService
{
    public const TEMPLATE_RELATIVE_PATH = 'templates/contracts/report.docx';

    /**
     * @return array{binary: string, filename: string}
     */
    public function generate(ContractReport $report): array
    {
        $report->loadMissing([
            'contract.author',
            'contract.course',
            'assignment.studyGroup',
        ]);

        $contract = $report->contract;
        $assignment = $report->assignment;
        $author = $contract->author;
        $course = $contract->course;

        $authorName = $author->full_name ?: ($author->name ?? '—');
        $authorPseudonym = $author->name ?: $authorName;
        $courseTitle = $course->title ?? '—';
        $courseHours = $course?->studyHoursFullLabel() ?? '—';
        $studyForm = $course?->studyFormGenitiveLabel() ?? '____________';
        $programDative = match ($course->type ?? null) {
            'professional_retraining' => 'дополнительной профессиональной программе профессиональной переподготовки',
            'advanced_training' => 'дополнительной профессиональной программе повышения квалификации',
            'professional_education' => 'программе профессионального обучения',
            default => 'программе',
        };

        $workTypes = trim((string) $assignment->work_types);
        if ($workTypes === '') {
            $workTypes = '—';
        }

        $isPerStudent = $report->isPerStudent();
        if ($isPerStudent) {
            $students = $report->successfulStudentsList();
            $lines = [];
            foreach ($students as $index => $name) {
                $lines[] = ($index + 1).'. '.$name;
            }
            $lessonUrl = $lines !== [] ? implode("\n", $lines) : '—';
            $lessonHours = (string) $report->successfulStudentsCount();
        } else {
            $lessonUrl = $report->lesson_url ?: '____';
            $lessonHours = $report->lesson_hours !== null ? $report->lessonHoursFormatted() : '____';
        }

        $replacements = [
            '{{REPORT_NUMBER}}' => $this->escapeXml($report->displayNumber()),
            '{{ASSIGNMENT_NUMBER}}' => $this->escapeXml((string) $assignment->number),
            '{{ASSIGNMENT_DATE}}' => $this->escapeXml($assignment->issuedAtLabel()),
            '{{CONTRACT_NUMBER}}' => $this->escapeXml((string) ($contract->number ?: '________')),
            '{{CONTRACT_DATE}}' => $this->escapeXml($contract->signedAtLabel()),
            '{{REPORT_DATE}}' => $this->escapeXml($report->issuedAtLabel()),
            '{{AUTHOR_NAME}}' => $this->escapeXml($authorName),
            '{{AUTHOR_PSEUDONYM}}' => $this->escapeXml($authorPseudonym),
            '{{NAP_DATE}}' => $this->escapeXml($contract->napRegisteredAtLabel()),
            '{{STARTS_AT}}' => $this->escapeXml($report->startsAtShortLabel()),
            '{{ENDS_AT}}' => $this->escapeXml($report->endsAtShortLabel()),
            '{{PROGRAM_DATIVE}}' => $this->escapeXml($programDative),
            '{{COURSE_TITLE}}' => $this->escapeXml($courseTitle),
            '{{COURSE_HOURS}}' => $this->escapeXml($courseHours),
            '{{STUDY_GROUP}}' => $this->escapeXml($assignment->studyGroupLabel()),
            '{{STUDY_FORM}}' => $this->escapeXml($studyForm),
            '{{WORK_TYPES}}' => $this->escapeXmlMultiline($workTypes),
            '{{LESSON_URL}}' => $this->escapeXmlMultiline($lessonUrl),
            '{{LESSON_HOURS}}' => $this->escapeXml($lessonHours),
        ];

        $filename = 'otchet-'.Str::slug($report->displayNumber()).'-dogovor-'.Str::slug((string) $contract->number);
        $nameSlug = Str::slug($authorName);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        return [
            'binary' => $this->fillTemplate($replacements, $isPerStudent),
            'filename' => $filename.'.docx',
        ];
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected function fillTemplate(array $replacements, bool $isPerStudent = false): string
    {
        $templatePath = resource_path(self::TEMPLATE_RELATIVE_PATH);
        if (! is_file($templatePath)) {
            throw new RuntimeException('Шаблон отчёта не найден');
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'contract_report_');
        if ($tempPath === false) {
            throw new RuntimeException('Не удалось создать временный файл');
        }

        $workingPath = $tempPath.'.docx';
        if (! @copy($templatePath, $workingPath)) {
            @unlink($tempPath);
            throw new RuntimeException('Не удалось подготовить шаблон отчёта');
        }
        @unlink($tempPath);

        $zip = new ZipArchive();
        if ($zip->open($workingPath) !== true) {
            @unlink($workingPath);
            throw new RuntimeException('Не удалось открыть шаблон отчёта');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            @unlink($workingPath);
            throw new RuntimeException('Повреждён шаблон отчёта');
        }

        if ($isPerStudent) {
            $documentXml = str_replace(
                [
                    'Ссылка на проведенное занятие',
                    'Количество проведенных акад. часов',
                ],
                [
                    'Успешно завершившие обучение (Фамилия И.О.)',
                    'Количество слушателей',
                ],
                $documentXml
            );
        }

        $filledXml = strtr($documentXml, $replacements);
        foreach (array_keys($replacements) as $placeholder) {
            if (str_contains($filledXml, $placeholder)) {
                $zip->close();
                @unlink($workingPath);
                throw new RuntimeException('Не удалось подставить данные в шаблон отчёта: '.$placeholder);
            }
        }

        $zip->deleteName('word/document.xml');
        $zip->addFromString('word/document.xml', $filledXml);
        $zip->close();

        $binary = file_get_contents($workingPath);
        @unlink($workingPath);

        if ($binary === false || $binary === '') {
            throw new RuntimeException('Не удалось сформировать файл отчёта');
        }

        return $binary;
    }

    protected function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    protected function escapeXmlMultiline(string $value): string
    {
        $lines = preg_split("/\r\n|\r|\n/u", $value) ?: [''];
        $escaped = array_map(fn (string $line) => $this->escapeXml($line), $lines);

        return implode('</w:t><w:br/><w:t xml:space="preserve">', $escaped);
    }
}
