<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesScannedPdf;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractAct;
use App\Models\ContractAssignment;
use App\Models\ContractWorkflowStatus;
use App\Service\ContractActDocumentService;
use App\Service\ContractActivityLogger;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContractActController extends Controller
{
    use ManagesScannedPdf;

    public function createForContract(Contract $contract, Request $request)
    {
        $kind = $this->resolveKind($request);
        $contract->loadMissing([
            'author',
            'course',
            'assignments.report',
            'assignments.perStudentReport',
            'assignments.perStudentAct',
            'assignments.studyGroup',
        ]);

        $assignments = $contract->assignments->filter(function (ContractAssignment $a) use ($kind) {
            if ($kind === ContractAct::KIND_PER_STUDENT) {
                return $a->isPaymentPerStudent() && $a->perStudentReport && ! $a->perStudentAct;
            }

            return $a->hasBasePayment();
        });

        if ($assignments->isEmpty()) {
            return redirect()
                ->route('admin.contract.show', $contract)
                ->with('error', $kind === ContractAct::KIND_PER_STUDENT
                    ? 'Нет заданий для акта N.2 (нужен отчёт N.2 без акта)'
                    : 'Сначала создайте задание');
        }

        $assignmentId = (int) $request->query('assignment_id');
        $assignment = $assignments->firstWhere('id', $assignmentId)
            ?: $assignments->first();

        return view('admin.contract.act.create', [
            'contract' => $contract,
            'assignment' => $assignment,
            'assignments' => $assignments->values(),
            'fixedAssignment' => false,
            'nextNumber' => $kind === ContractAct::KIND_PER_STUDENT
                ? (int) $assignment->number
                : $this->nextActNumber($contract),
            'suggestedAmount' => $kind === ContractAct::KIND_PER_STUDENT
                ? ContractAct::suggestedPerStudentAmountForAssignment($assignment)
                : ContractAct::suggestedAmountForAssignment($assignment),
            'kind' => $kind,
        ]);
    }

    public function createForAssignment(ContractAssignment $assignment, Request $request)
    {
        $kind = $this->resolveKind($request);
        $assignment->loadMissing([
            'contract.author',
            'contract.course',
            'report',
            'perStudentReport',
            'perStudentAct',
            'studyGroup',
        ]);

        if ($kind === ContractAct::KIND_PER_STUDENT) {
            if (! $assignment->isPaymentPerStudent()) {
                return redirect()
                    ->route('admin.assignment.show', $assignment)
                    ->with('error', 'В задании не включена оплата за успешно обученного');
            }
            if (! $assignment->perStudentReport) {
                return redirect()
                    ->route('admin.assignment.show', $assignment)
                    ->with('error', 'Сначала создайте отчёт N.2');
            }
            if ($assignment->perStudentAct) {
                return redirect()
                    ->route('admin.contract_act.show', $assignment->perStudentAct)
                    ->with('success', 'Акт N.2 по этому заданию уже создан');
            }
        } elseif (! $assignment->hasBasePayment()) {
            return redirect()
                ->route('admin.assignment.show', $assignment)
                ->with('error', 'Для этого задания доступен только акт N.2');
        }

        return view('admin.contract.act.create', [
            'contract' => $assignment->contract,
            'assignment' => $assignment,
            'assignments' => collect([$assignment]),
            'fixedAssignment' => true,
            'nextNumber' => $kind === ContractAct::KIND_PER_STUDENT
                ? (int) $assignment->number
                : $this->nextActNumber($assignment->contract),
            'suggestedAmount' => $kind === ContractAct::KIND_PER_STUDENT
                ? ContractAct::suggestedPerStudentAmountForAssignment($assignment)
                : ContractAct::suggestedAmountForAssignment($assignment),
            'kind' => $kind,
        ]);
    }

    public function storeForContract(Request $request, Contract $contract)
    {
        $data = $this->validatedActData($request, $contract);
        $assignment = ContractAssignment::query()
            ->where('contract_id', $contract->id)
            ->findOrFail($data['assignment_id']);

        $this->ensureAssignmentAllowsAct($assignment, $data['kind']);
        $act = $this->createAct($contract, $assignment, $data);

        return redirect()
            ->route('admin.contract_act.show', $act)
            ->with('success', 'Акт создан');
    }

    public function storeForAssignment(Request $request, ContractAssignment $assignment)
    {
        $assignment->loadMissing('contract');
        $data = $this->validatedActData($request, $assignment->contract, $assignment);
        $this->ensureAssignmentAllowsAct($assignment, $data['kind']);
        $act = $this->createAct($assignment->contract, $assignment, $data);

        return redirect()
            ->route('admin.contract_act.show', $act)
            ->with('success', 'Акт создан');
    }

    public function show(ContractAct $act)
    {
        $act->load([
            'contract.author',
            'contract.course',
            'assignment.studyGroup',
            'assignment.report',
        ]);

        return view('admin.contract.act.show', compact('act'));
    }

    public function edit(ContractAct $act)
    {
        $act->load(['contract.author', 'contract.course', 'assignment.report', 'assignment.perStudentReport']);

        return view('admin.contract.act.edit', compact('act'));
    }

    public function update(Request $request, ContractAct $act, ContractActivityLogger $activityLogger)
    {
        $data = $this->validatedActData($request, $act->contract, $act->assignment, $act);
        unset($data['assignment_id']);
        $before = $activityLogger->snapshot($act);
        $act->update($data);
        $activityLogger->updated(
            $act,
            $before,
            $activityLogger->snapshot($act->fresh()),
            $act->contract
        );

        return redirect()
            ->route('admin.contract_act.show', $act)
            ->with('success', 'Акт обновлён');
    }

    public function destroy(ContractAct $act, ContractActivityLogger $activityLogger)
    {
        $contract = $act->contract;
        $activityLogger->deleted($act, $contract);
        $act->delete();

        return redirect()
            ->route('admin.contract.show', $contract)
            ->with('success', 'Акт удалён');
    }

    public function uploadScan(Request $request, ContractAct $act)
    {
        return $this->uploadScannedPdf(
            $request,
            $act,
            'contracts/scanned/acts',
            'admin.contract_act.show',
            $act
        );
    }

    public function downloadScan(ContractAct $act)
    {
        return $this->downloadScannedPdf(
            $act,
            $act->scannedPdfDownloadName('skan-akt-'.$act->number)
        );
    }

    public function destroyScan(ContractAct $act)
    {
        return $this->destroyScannedPdf($act, 'admin.contract_act.show', $act);
    }

    public function generate(ContractAct $act)
    {
        $act->loadMissing([
            'contract.author',
            'contract.course',
            'assignment.studyGroup',
        ]);

        $contract = $act->contract;
        $author = $contract->author;
        $filename = 'akt-'.Str::slug($act->displayNumber()).'-dogovor-'.Str::slug((string) $contract->number);
        $nameSlug = Str::slug($author->full_name ?: $author->name);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        /** @var DomPdfWrapper $pdf */
        $pdf = app('dompdf.wrapper');
        $this->registerContractFonts($pdf);

        $pdf->loadView('admin.contract.templates.act', [
            'act' => $act,
            'assignment' => $act->assignment,
            'contract' => $contract,
            'author' => $author,
            'course' => $contract->course,
        ])->setPaper('a4');

        $this->addCenteredPageNumbers($pdf);

        return $pdf->download($filename.'.pdf');
    }

    public function generateDocx(ContractAct $act, ContractActDocumentService $documentService)
    {
        $document = $documentService->generate($act);

        return response($document['binary'], 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="'.$document['filename'].'"',
        ]);
    }

    private function createAct(Contract $contract, ContractAssignment $assignment, array $data): ContractAct
    {
        if (($data['kind'] ?? ContractAct::KIND_MAIN) === ContractAct::KIND_PER_STUDENT) {
            ContractAct::onlyTrashed()
                ->where('assignment_id', $assignment->id)
                ->where('kind', ContractAct::KIND_PER_STUDENT)
                ->forceDelete();
        }

        $act = $contract->acts()->create([
            ...$data,
            'assignment_id' => $assignment->id,
        ]);

        app(ContractActivityLogger::class)->created($act, $contract);

        return $act;
    }

    private function nextActNumber(Contract $contract): int
    {
        return max(1, (int) $contract->acts()->where('kind', ContractAct::KIND_MAIN)->max('number') + 1);
    }

    private function resolveKind(Request $request): string
    {
        $kind = (string) $request->input('kind', $request->query('kind', ContractAct::KIND_MAIN));

        return in_array($kind, [ContractAct::KIND_MAIN, ContractAct::KIND_PER_STUDENT], true)
            ? $kind
            : ContractAct::KIND_MAIN;
    }

    private function ensureAssignmentAllowsAct(ContractAssignment $assignment, string $kind): void
    {
        if ($kind === ContractAct::KIND_PER_STUDENT) {
            if (! $assignment->isPaymentPerStudent()) {
                throw ValidationException::withMessages([
                    'kind' => 'В задании не включена оплата за успешно обученного',
                ]);
            }
            if (! $assignment->perStudentReport) {
                throw ValidationException::withMessages([
                    'assignment_id' => 'Сначала создайте отчёт N.2',
                ]);
            }
            if ($assignment->perStudentAct()->exists()) {
                throw ValidationException::withMessages([
                    'assignment_id' => 'Акт N.2 по этому заданию уже создан',
                ]);
            }

            return;
        }

        if (! $assignment->hasBasePayment()) {
            throw ValidationException::withMessages([
                'kind' => 'Для этого задания доступен только акт N.2',
            ]);
        }
    }

    private function validatedActData(
        Request $request,
        Contract $contract,
        ?ContractAssignment $fixedAssignment = null,
        ?ContractAct $act = null
    ): array {
        $assignmentRule = [
            'required',
            'integer',
            Rule::exists('contract_assignments', 'id')->where(function ($query) use ($contract) {
                $query->where('contract_id', $contract->id)->whereNull('deleted_at');
            }),
        ];

        if ($act) {
            $assignmentRule = ['nullable', 'integer'];
        } elseif ($fixedAssignment) {
            $assignmentRule[] = Rule::in([$fixedAssignment->id]);
        }

        $kind = $act?->kind ?: $this->resolveKind($request);

        $data = $request->validate([
            'assignment_id' => $assignmentRule,
            'kind' => ['nullable', 'string', Rule::in([ContractAct::KIND_MAIN, ContractAct::KIND_PER_STUDENT])],
            'number' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(ContractWorkflowStatus::keys())],
            'issued_at' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ], [
            'assignment_id.required' => 'Выберите задание',
            'assignment_id.exists' => 'Выбранное задание не найдено',
            'assignment_id.in' => 'Недопустимое задание',
            'number.required' => 'Укажите номер акта',
            'number.integer' => 'Номер акта должен быть числом',
            'status.required' => 'Выберите статус',
            'status.in' => 'Выбран недопустимый статус',
            'issued_at.date' => 'Укажите корректную дату акта',
            'amount.numeric' => 'Сумма должна быть числом',
        ]);

        $data['kind'] = $act?->kind ?: $kind;

        if ($fixedAssignment) {
            $data['assignment_id'] = $fixedAssignment->id;
        }

        if ($data['kind'] === ContractAct::KIND_PER_STUDENT) {
            $numberSource = $fixedAssignment
                ?: ($act?->assignment)
                ?: ContractAssignment::query()->find($data['assignment_id'] ?? null);
            if ($numberSource) {
                $data['number'] = (int) $numberSource->number;
            }
        }

        if (($data['issued_at'] ?? null) === '') {
            $data['issued_at'] = null;
        }

        if (($data['amount'] ?? null) === '') {
            $data['amount'] = null;
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
