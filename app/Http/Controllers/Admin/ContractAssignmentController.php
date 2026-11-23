<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractAssignment;
use App\Models\ContractWorkflowStatus;
use App\Models\StudyGroup;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContractAssignmentController extends Controller
{
    public function create(Contract $contract)
    {
        $contract->loadMissing(['course', 'author']);
        $studyGroups = $this->studyGroupsForContract($contract);

        return view('admin.contract.assignment.create', compact('contract', 'studyGroups'));
    }

    public function store(Request $request, Contract $contract)
    {
        $data = $this->validatedAssignmentData($request, $contract);

        if (! $contract->assignments()->exists()) {
            // Нумерация с 1: удаляем soft-deleted, иначе unique (contract_id, number) конфликтует.
            $contract->assignments()->onlyTrashed()->forceDelete();
            $nextNumber = 1;
        } else {
            $nextNumber = (int) $contract->assignments()->max('number') + 1;
            $contract->assignments()->onlyTrashed()->where('number', $nextNumber)->forceDelete();
        }

        $assignment = $contract->assignments()->create([
            ...$data,
            'number' => $nextNumber,
        ]);

        return redirect()
            ->route('admin.assignment.show', $assignment)
            ->with('success', 'Задание создано');
    }

    public function show(ContractAssignment $assignment)
    {
        $assignment->load(['contract.author', 'contract.course', 'studyGroup', 'report', 'acts']);

        $breadcrumbs = [
            ['title' => 'Обучение', 'url' => null],
            ['title' => 'Договоры', 'url' => 'admin.contract.index'],
            [
                'title' => 'Договор №'.$assignment->contract->number,
                'url' => route('admin.contract.show', $assignment->contract),
            ],
            ['title' => 'Задание №'.$assignment->number, 'url' => null],
        ];

        return view('admin.contract.assignment.show', compact('assignment', 'breadcrumbs'));
    }

    public function edit(ContractAssignment $assignment)
    {
        $assignment->load(['contract.course', 'contract.author']);
        $studyGroups = $this->studyGroupsForContract($assignment->contract);

        return view('admin.contract.assignment.edit', compact('assignment', 'studyGroups'));
    }

    public function update(Request $request, ContractAssignment $assignment)
    {
        $data = $this->validatedAssignmentData($request, $assignment->contract);
        $assignment->update($data);

        return redirect()
            ->route('admin.assignment.show', $assignment)
            ->with('success', 'Задание обновлено');
    }

    public function destroy(ContractAssignment $assignment)
    {
        $contract = $assignment->contract;
        $assignment->delete();

        return redirect()
            ->route('admin.contract.show', $contract)
            ->with('success', 'Задание удалено');
    }

    public function generate(ContractAssignment $assignment)
    {
        $assignment->loadMissing([
            'contract.author',
            'contract.course',
            'contract.project',
            'studyGroup',
        ]);

        $contract = $assignment->contract;
        $author = $contract->author;
        $filename = 'zadanie-'.$assignment->number.'-dogovor-'.Str::slug((string) $contract->number);
        $nameSlug = Str::slug($author->full_name ?: $author->name);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        /** @var DomPdfWrapper $pdf */
        $pdf = app('dompdf.wrapper');
        $this->registerContractFonts($pdf);

        $pdf->loadView('admin.contract.templates.assignment', [
            'assignment' => $assignment,
            'contract' => $contract,
            'author' => $author,
            'course' => $contract->course,
            'studyGroup' => $assignment->studyGroup,
        ])->setPaper('a4');

        $this->addCenteredPageNumbers($pdf);

        return $pdf->download($filename.'.pdf');
    }

    private function validatedAssignmentData(Request $request, Contract $contract): array
    {
        $courseId = (int) $contract->course_id;
        $presetKeys = array_keys(ContractAssignment::workTypePresetLabels());

        $data = $request->validate([
            'study_group_id' => [
                'required',
                'integer',
                Rule::exists('study_groups', 'id')->where(function ($query) use ($courseId) {
                    $query->where('course_id', $courseId)->whereNull('deleted_at');
                }),
            ],
            'work_type_codes' => ['required', 'array', 'min:1'],
            'work_type_codes.*' => ['integer', Rule::in($presetKeys)],
            'program_url' => ['nullable', 'string', 'max:2000'],
            'schedule_url' => ['nullable', 'string', 'max:2000'],
            'notebook_url' => ['nullable', 'string', 'max:2000'],
            'custom_work_type' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(ContractWorkflowStatus::keys())],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'issued_at' => ['nullable', 'date'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
        ], [
            'study_group_id.required' => 'Выберите учебную группу',
            'study_group_id.exists' => 'Выбранная учебная группа не найдена у курса договора',
            'work_type_codes.required' => 'Выберите хотя бы один вид работ',
            'work_type_codes.min' => 'Выберите хотя бы один вид работ',
            'work_type_codes.*.in' => 'Выбран недопустимый вид работ',
            'custom_work_type.required' => 'Заполните произвольный вид работ',
            'status.required' => 'Выберите статус',
            'status.in' => 'Выбран недопустимый статус',
            'hourly_rate.required' => 'Укажите стоимость часа',
            'hourly_rate.numeric' => 'Стоимость часа должна быть числом',
            'hourly_rate.min' => 'Стоимость часа не может быть отрицательной',
            'issued_at.date' => 'Укажите корректную дату задания',
            'starts_at.required' => 'Укажите дату начала оказания услуг',
            'starts_at.date' => 'Укажите корректную дату начала оказания услуг',
            'ends_at.required' => 'Укажите дату окончания оказания услуг',
            'ends_at.date' => 'Укажите корректную дату окончания оказания услуг',
            'ends_at.after_or_equal' => 'Дата окончания не может быть раньше даты начала',
        ]);

        $codes = array_values(array_unique(array_map('intval', $data['work_type_codes'])));
        sort($codes);

        $programUrl = trim((string) ($data['program_url'] ?? ''));
        $scheduleUrl = trim((string) ($data['schedule_url'] ?? ''));
        $notebookUrl = trim((string) ($data['notebook_url'] ?? ''));
        $customWorkType = trim((string) ($data['custom_work_type'] ?? ''));

        if (in_array(ContractAssignment::WORK_TYPE_CUSTOM, $codes, true) && $customWorkType === '') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'custom_work_type' => 'Заполните произвольный вид работ',
            ]);
        }

        if (! in_array(ContractAssignment::WORK_TYPE_CUSTOM, $codes, true)) {
            $customWorkType = '';
        }

        $data['work_type_codes'] = $codes;
        $data['program_url'] = $programUrl !== '' ? $programUrl : null;
        $data['schedule_url'] = $scheduleUrl !== '' ? $scheduleUrl : null;
        $data['notebook_url'] = $notebookUrl !== '' ? $notebookUrl : null;
        $data['custom_work_type'] = $customWorkType !== '' ? $customWorkType : null;
        $data['work_types'] = ContractAssignment::buildWorkTypesText(
            $codes,
            $data['program_url'],
            $data['schedule_url'],
            $data['notebook_url'],
            $data['custom_work_type']
        );

        if (($data['issued_at'] ?? null) === '') {
            $data['issued_at'] = null;
        }

        return $data;
    }

    private function studyGroupsForContract(Contract $contract)
    {
        if (! $contract->course_id) {
            return collect();
        }

        return StudyGroup::query()
            ->where('course_id', $contract->course_id)
            ->orderBy('starts_at')
            ->orderBy('title')
            ->get();
    }

    private function addCenteredPageNumbers(DomPdfWrapper $pdf): void
    {
        $pdf->getDomPDF()->setCallbacks([
            [
                'event' => 'end_document',
                'f' => function (int $pageNumber, int $pageCount, $canvas, $fontMetrics): void {
                    $text = (string) $pageNumber;
                    $font = $fontMetrics->getFont('Times New Roman')
                        ?: $fontMetrics->getFont('DejaVu Sans');
                    $size = 10;
                    $width = $fontMetrics->getTextWidth($text, $font, $size);
                    $x = ($canvas->get_width() - $width) / 2;
                    $y = $canvas->get_height() - 42;

                    $canvas->text($x, $y, $text, $font, $size);
                },
            ],
        ]);
    }

    private function registerContractFonts(DomPdfWrapper $pdf): void
    {
        $fontMetrics = $pdf->getDomPDF()->getFontMetrics();
        $dir = resource_path('fonts/contract');

        $variants = [
            ['style' => 'normal', 'weight' => 'normal', 'file' => 'TimesNewRoman.ttf'],
            ['style' => 'normal', 'weight' => 'bold', 'file' => 'TimesNewRoman-Bold.ttf'],
            ['style' => 'italic', 'weight' => 'normal', 'file' => 'TimesNewRoman-Italic.ttf'],
            ['style' => 'italic', 'weight' => 'bold', 'file' => 'TimesNewRoman-BoldItalic.ttf'],
        ];

        foreach ($variants as $variant) {
            $path = $dir.'/'.$variant['file'];
            if (! is_file($path)) {
                continue;
            }

            $fontMetrics->registerFont([
                'family' => 'Times New Roman',
                'style' => $variant['style'],
                'weight' => $variant['weight'],
            ], $path);
        }

        $pdf->getDomPDF()->getOptions()->setDefaultFont('Times New Roman');
    }
}
