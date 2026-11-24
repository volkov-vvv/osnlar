<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesScannedPdf;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractAssignment;
use App\Models\ContractReport;
use App\Models\ContractWorkflowStatus;
use App\Service\ContractActivityLogger;
use App\Service\ContractReportDocumentService;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContractReportController extends Controller
{
    use ManagesScannedPdf;

    public function createForContract(Contract $contract, Request $request)
    {
        $kind = $this->resolveKind($request);
        $contract->loadMissing(['author', 'course', 'assignments.report', 'assignments.perStudentReport', 'assignments.studyGroup']);

        $availableAssignments = $contract->assignments->filter(function (ContractAssignment $a) use ($kind) {
            if ($kind === ContractReport::KIND_PER_STUDENT) {
                return $a->isPaymentPerStudent() && ! $a->perStudentReport;
            }

            return $a->hasBasePayment() && ! $a->report;
        });

        if ($availableAssignments->isEmpty()) {
            return redirect()
                ->route('admin.contract.show', $contract)
                ->with('error', $kind === ContractReport::KIND_PER_STUDENT
                    ? 'Нет заданий для отчёта N.2'
                    : 'Нет заданий без отчёта');
        }

        $assignmentId = (int) $request->query('assignment_id');
        $assignment = $availableAssignments->firstWhere('id', $assignmentId)
            ?: $availableAssignments->first();

        return view('admin.contract.report.create', [
            'contract' => $contract,
            'assignment' => $assignment,
            'assignments' => $availableAssignments->values(),
            'fixedAssignment' => false,
            'kind' => $kind,
        ]);
    }

    public function createForAssignment(ContractAssignment $assignment, Request $request)
    {
        $kind = $this->resolveKind($request);
        $assignment->loadMissing(['contract.author', 'contract.course', 'report', 'perStudentReport']);

        if ($kind === ContractReport::KIND_PER_STUDENT) {
            if (! $assignment->isPaymentPerStudent()) {
                return redirect()
                    ->route('admin.assignment.show', $assignment)
                    ->with('error', 'В задании не включена оплата за успешно обученного');
            }
            if ($assignment->perStudentReport) {
                return redirect()
                    ->route('admin.contract_report.show', $assignment->perStudentReport)
                    ->with('success', 'Отчёт N.2 по этому заданию уже создан');
            }
        } else {
            if (! $assignment->hasBasePayment()) {
                return redirect()
                    ->route('admin.assignment.show', $assignment)
                    ->with('error', 'Для этого задания доступен только отчёт N.2');
            }
            if ($assignment->report) {
                return redirect()
                    ->route('admin.contract_report.show', $assignment->report)
                    ->with('success', 'Отчёт по этому заданию уже создан');
            }
        }

        return view('admin.contract.report.create', [
            'contract' => $assignment->contract,
            'assignment' => $assignment,
            'assignments' => collect([$assignment]),
            'fixedAssignment' => true,
            'kind' => $kind,
        ]);
    }

    public function storeForContract(Request $request, Contract $contract)
    {
        $data = $this->validatedReportData($request, $contract);
        $assignment = ContractAssignment::query()
            ->where('contract_id', $contract->id)
            ->findOrFail($data['assignment_id']);

        $this->ensureAssignmentAllowsReport($assignment, $data['kind']);

        $report = $this->createReport($contract, $assignment, $data);

        return redirect()
            ->route('admin.contract_report.show', $report)
            ->with('success', 'Отчёт создан');
    }

    public function storeForAssignment(Request $request, ContractAssignment $assignment)
    {
        $assignment->loadMissing('contract');
        $data = $this->validatedReportData($request, $assignment->contract, $assignment);
        $this->ensureAssignmentAllowsReport($assignment, $data['kind']);

        $report = $this->createReport($assignment->contract, $assignment, $data);

        return redirect()
            ->route('admin.contract_report.show', $report)
            ->with('success', 'Отчёт создан');
    }

    public function show(ContractReport $report)
    {
        $report->load([
            'contract.author',
            'contract.course',
            'assignment.studyGroup',
        ]);

        return view('admin.contract.report.show', compact('report'));
    }

    public function edit(ContractReport $report)
    {
        $report->load(['contract.author', 'contract.course', 'assignment']);

        return view('admin.contract.report.edit', compact('report'));
    }

    public function update(Request $request, ContractReport $report, ContractActivityLogger $activityLogger)
    {
        $data = $this->validatedReportData($request, $report->contract, $report->assignment, $report);
        unset($data['assignment_id']);
        $before = $activityLogger->snapshot($report);
        $report->update($data);
        $activityLogger->updated(
            $report,
            $before,
            $activityLogger->snapshot($report->fresh()),
            $report->contract
        );

        return redirect()
            ->route('admin.contract_report.show', $report)
            ->with('success', 'Отчёт обновлён');
    }

    public function destroy(ContractReport $report, ContractActivityLogger $activityLogger)
    {
        $contract = $report->contract;
        $activityLogger->deleted($report, $contract);
        $report->delete();

        return redirect()
            ->route('admin.contract.show', $contract)
            ->with('success', 'Отчёт удалён');
    }

    public function uploadScan(Request $request, ContractReport $report)
    {
        return $this->uploadScannedPdf(
            $request,
            $report,
            'contracts/scanned/reports',
            'admin.contract_report.show',
            $report
        );
    }

    public function downloadScan(ContractReport $report)
    {
        return $this->downloadScannedPdf(
            $report,
            $report->scannedPdfDownloadName('skan-otchet-'.$report->number)
        );
    }

    public function destroyScan(ContractReport $report)
    {
        return $this->destroyScannedPdf($report, 'admin.contract_report.show', $report);
    }

    public function generate(ContractReport $report)
    {
        $report->loadMissing([
            'contract.author',
            'contract.course',
            'assignment.studyGroup',
        ]);

        $contract = $report->contract;
        $author = $contract->author;
        $filename = 'otchet-'.Str::slug($report->displayNumber()).'-dogovor-'.Str::slug((string) $contract->number);
        $nameSlug = Str::slug($author->full_name ?: $author->name);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        /** @var DomPdfWrapper $pdf */
        $pdf = app('dompdf.wrapper');
        $this->registerContractFonts($pdf);

        $pdf->loadView('admin.contract.templates.report', [
            'report' => $report,
            'assignment' => $report->assignment,
            'contract' => $contract,
            'author' => $author,
            'course' => $contract->course,
        ])->setPaper('a4');

        $this->addCenteredPageNumbers($pdf);

        return $pdf->download($filename.'.pdf');
    }

    public function generateDocx(ContractReport $report, ContractReportDocumentService $documentService)
    {
        $document = $documentService->generate($report);

        return response($document['binary'], 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="'.$document['filename'].'"',
        ]);
    }

    private function createReport(Contract $contract, ContractAssignment $assignment, array $data): ContractReport
    {
        $kind = $data['kind'] ?? ContractReport::KIND_MAIN;

        // Soft-deleted отчёт того же kind мешает unique (assignment_id, kind).
        ContractReport::onlyTrashed()
            ->where('assignment_id', $assignment->id)
            ->where('kind', $kind)
            ->forceDelete();

        $report = $contract->reports()->create([
            ...$data,
            'assignment_id' => $assignment->id,
        ]);

        app(ContractActivityLogger::class)->created($report, $contract);

        return $report;
    }

    private function ensureAssignmentAllowsReport(ContractAssignment $assignment, string $kind): void
    {
        if ($kind === ContractReport::KIND_PER_STUDENT) {
            if (! $assignment->isPaymentPerStudent()) {
                throw ValidationException::withMessages([
                    'kind' => 'В задании не включена оплата за успешно обученного',
                ]);
            }
            if ($assignment->perStudentReport()->exists()) {
                throw ValidationException::withMessages([
                    'assignment_id' => 'Отчёт N.2 по этому заданию уже создан',
                ]);
            }

            return;
        }

        if (! $assignment->hasBasePayment()) {
            throw ValidationException::withMessages([
                'kind' => 'Для этого задания доступен только отчёт N.2',
            ]);
        }
        if ($assignment->report()->exists()) {
            throw ValidationException::withMessages([
                'assignment_id' => 'По этому заданию отчёт уже создан',
            ]);
        }
    }

    private function resolveKind(Request $request): string
    {
        $kind = (string) $request->input('kind', $request->query('kind', ContractReport::KIND_MAIN));

        return in_array($kind, [ContractReport::KIND_MAIN, ContractReport::KIND_PER_STUDENT], true)
            ? $kind
            : ContractReport::KIND_MAIN;
    }

    private function validatedReportData(
        Request $request,
        Contract $contract,
        ?ContractAssignment $fixedAssignment = null,
        ?ContractReport $report = null
    ): array {
        $assignmentRule = [
            'required',
            'integer',
            Rule::exists('contract_assignments', 'id')->where(function ($query) use ($contract) {
                $query->where('contract_id', $contract->id)->whereNull('deleted_at');
            }),
        ];

        if ($report) {
            // При обновлении assignment не меняем.
            $assignmentRule = ['nullable', 'integer'];
        } elseif ($fixedAssignment) {
            $assignmentRule[] = Rule::in([$fixedAssignment->id]);
        }

        $kind = $report?->kind ?: $this->resolveKind($request);
        $isPerStudent = $kind === ContractReport::KIND_PER_STUDENT;

        $rules = [
            'assignment_id' => $assignmentRule,
            'kind' => ['nullable', 'string', Rule::in([ContractReport::KIND_MAIN, ContractReport::KIND_PER_STUDENT])],
            'number' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(ContractWorkflowStatus::keys())],
            'issued_at' => ['nullable', 'date'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'lesson_url' => ['nullable', 'string', 'max:2000'],
            'lesson_hours' => ['nullable', 'numeric', 'min:0'],
            'successful_students' => [$isPerStudent ? 'required' : 'nullable', 'array', $isPerStudent ? 'min:1' : 'nullable'],
            'successful_students.*' => ['nullable', 'string', 'max:255'],
        ];

        $data = $request->validate($rules, [
            'assignment_id.required' => 'Выберите задание',
            'assignment_id.exists' => 'Выбранное задание не найдено',
            'assignment_id.in' => 'Недопустимое задание',
            'number.required' => 'Укажите номер отчёта',
            'number.integer' => 'Номер отчёта должен быть числом',
            'status.required' => 'Выберите статус',
            'status.in' => 'Выбран недопустимый статус',
            'issued_at.date' => 'Укажите корректную дату отчёта',
            'starts_at.required' => 'Укажите дату начала оказания услуг',
            'ends_at.required' => 'Укажите дату окончания оказания услуг',
            'ends_at.after_or_equal' => 'Дата окончания не может быть раньше даты начала',
            'lesson_hours.numeric' => 'Количество часов должно быть числом',
            'successful_students.required' => 'Добавьте хотя бы одного слушателя',
            'successful_students.min' => 'Добавьте хотя бы одного слушателя',
        ]);

        $data['kind'] = $report?->kind ?: $kind;

        if ($fixedAssignment) {
            $data['assignment_id'] = $fixedAssignment->id;
        }

        if (($data['issued_at'] ?? null) === '') {
            $data['issued_at'] = null;
        }

        if ($data['kind'] === ContractReport::KIND_PER_STUDENT) {
            $students = collect($data['successful_students'] ?? [])
                ->map(fn ($name) => trim((string) $name))
                ->filter()
                ->values()
                ->all();

            if ($students === []) {
                throw ValidationException::withMessages([
                    'successful_students' => 'Добавьте хотя бы одного слушателя',
                ]);
            }

            $data['successful_students'] = $students;
            $data['lesson_url'] = null;
            $data['lesson_hours'] = null;
            // Номер храним как номер задания; отображение — N.2
            $numberSource = $fixedAssignment
                ?: ($report?->assignment)
                ?: ContractAssignment::query()->find($data['assignment_id'] ?? null);
            if ($numberSource) {
                $data['number'] = (int) $numberSource->number;
            }
        } else {
            $data['successful_students'] = null;
            $lessonUrl = trim((string) ($data['lesson_url'] ?? ''));
            $data['lesson_url'] = $lessonUrl !== '' ? $lessonUrl : null;

            if (($data['lesson_hours'] ?? null) === '') {
                $data['lesson_hours'] = null;
            }
        }

        return $data;
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
