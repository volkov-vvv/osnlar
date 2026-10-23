<?php

namespace App\Exports;

use App\Models\BioLid;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

class BioLidsQuestionnaireExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithDefaultStyles, WithEvents
{
    private Collection $bioLids;

    /**
     * @param  array<int, int|string>  $ids
     */
    public function __construct(array $ids = [])
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));

        $query = BioLid::query()
            ->with(['course', 'user.studyGroups'])
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->orderBy('middlename');

        if ($ids !== []) {
            $query->whereIn('id', $ids);
        } else {
            // Без выбранных id выгружаем пустой набор (только шапка анкеты).
            $query->whereRaw('1 = 0');
        }

        $this->bioLids = $query->get();
    }

    public function collection(): Collection
    {
        return $this->bioLids;
    }

    public function headings(): array
    {
        return [
            'Фамилия',
            'Имя',
            'Отчество',
            'Дата рождения',
            'Пол',
            "СНИЛС\n(формат 111-222-333 00)",
            'Гражданство',
            "Адрес места жительства\n(регион, город, улица, дом, квартира)",
            "Образование (Высшее / среднее профессиональное),\nнаименование и реквизиты документа об образовании\n(название, серия, номер и дата выдачи документа)",
            'Полное наименование организации',
            'ИНН организации',
            'Должность',
            'Адрес электронной почты',
            'Номер телефона',
        ];
    }

    /**
     * @param  BioLid  $bioLid
     */
    public function map($bioLid): array
    {
        return [
            $bioLid->lastname,
            $bioLid->firstname,
            $bioLid->middlename ?: '',
            $bioLid->birth_date ? $bioLid->birth_date->format('d.m.Y') : '',
            $bioLid->genderShortLabel() ?? '',
            $bioLid->snils ?: '',
            $bioLid->citizenship ?: '',
            $bioLid->residence_address ?: '',
            $bioLid->education ?: '',
            $bioLid->organization_title ?: '',
            $bioLid->organization_inn ?: '',
            $bioLid->position ?: '',
            $bioLid->email,
            $bioLid->phoneFormatted(),
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'N';

                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells('A1:'.$lastColumn.'1');
                $sheet->setCellValue('A1', $this->questionnaireTitle());

                $headerFill = [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'BDC0BF',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];

                $sheet->getStyle('A1:'.$lastColumn.'1')->applyFromArray(array_merge($headerFill, [
                    'font' => [
                        'bold' => true,
                    ],
                ]));

                $sheet->getStyle('A2:'.$lastColumn.'2')->applyFromArray($headerFill);

                $sheet->getRowDimension(1)->setRowHeight(30);

                $lastRow = max(2, 2 + $this->bioLids->count());
                $sheet->getStyle('A1:'.$lastColumn.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '808080',
                            ],
                        ],
                    ],
                ]);

                // Фильтры по заголовкам колонок (со 2-й строки), без первой строки-заголовка анкеты.
                $sheet->setAutoFilter('A2:'.$lastColumn.$lastRow);
            },
        ];
    }

    private function questionnaireTitle(): string
    {
        $course = $this->bioLids
            ->pluck('course')
            ->filter()
            ->unique('id')
            ->first();

        $groupName = $this->bioLids
            ->flatMap(fn (BioLid $bioLid) => $bioLid->user?->studyGroups ?? [])
            ->unique('id')
            ->map(fn ($studyGroup) => $studyGroup->short_title ?: $studyGroup->title)
            ->filter()
            ->values()
            ->implode(', ');

        if ($groupName === '') {
            $groupName = 'НАЗВАНИЕ ГРУППЫ';
        }

        $courseName = $course?->title ?: 'НАЗВАНИЕ КУРСА';

        return 'Анкета обучающихся группа_№'.$groupName.'_'.$courseName;
    }
}
