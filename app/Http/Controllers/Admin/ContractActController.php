<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractAct;
use App\Models\ContractAssignment;
use App\Models\ContractWorkflowStatus;
use App\Service\ContractActDocumentService;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContractActController extends Controller
{
    public function createForContract(Contract $contract, Request $request)
    {
        $contract->loadMissing(['author', 'course', 'assignments.report', 'assignments.studyGroup']);

        if ($contract->assignments->isEmpty()) {
            return redirect()
                ->route('admin.contract.show', $contract)
                ->with('error', 'Сначала создайте задание');
        }

        $assignmentId = (int) $request->query('assignment_id');
        $assignment = $contract->assignments->firstWhere('id', $assignmentId)
            ?: $contract->assignments->first();

        return view('admin.contract.act.create', [
            'contract' => $contract,
            'assignment' => $assignment,
            'assignments' => $contract->assignments->values(),
            'fixedAssignment' => false,
            'nextNumber' => $this->nextActNumber($contract),
            'suggestedAmount' => ContractAct::suggestedAmountForAssignment($assignment),
        ]);
    }

    public function createForAssignment(ContractAssignment $assignment)
    {
        $assignment->loadMissing(['contract.author', 'contract.course', 'report', 'studyGroup']);

        return view('admin.contract.act.create', [
            'contract' => $assignment->contract,
            'assignment' => $assignment,
            'assignments' => collect([$assignment]),
            'fixedAssignment' => true,
            'nextNumber' => $this->nextActNumber($assignment->contract),
            'suggestedAmount' => ContractAct::suggestedAmountForAssignment($assignment),
        ]);
    }

    public function storeForContract(Request $request, Contract $contract)
    {
        $data = $this->validatedActData($request, $contract);
        $assignment = ContractAssignment::query()
            ->where('contract_id', $contract->id)
            ->findOrFail($data['assignment_id']);

        $act = $this->createAct($contract, $assignment, $data);

        return redirect()
            ->route('admin.contract_act.show', $act)
            ->with('success', 'Акт создан');
    }

    public function storeForAssignment(Request $request, ContractAssignment $assignment)
    {
        $assignment->loadMissing('contract');
        $data = $this->validatedActData($request, $assignment->contract, $assignment);
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
        $act->load(['contract.author', 'contract.course', 'assignment.report']);

        return view('admin.contract.act.edit', compact('act'));
    }

    public function update(Request $request, ContractAct $act)
    {
        $data = $this->validatedActData($request, $act->contract, $act->assignment, $act);
        unset($data['assignment_id']);
        $act->update($data);

        return redirect()
            ->route('admin.contract_act.show', $act)
            ->with('success', 'Акт обновлён');
    }

    public function destroy(ContractAct $act)
    {
        $contract = $act->contract;
        $act->delete();

        return redirect()
            ->route('admin.contract.show', $contract)
            ->with('success', 'Акт удалён');
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
        $filename = 'akt-'.$act->number.'-dogovor-'.Str::slug((string) $contract->number);
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
        return $contract->acts()->create([
            ...$data,
            'assignment_id' => $assignment->id,
        ]);
    }

    private function nextActNumber(Contract $contract): int
    {
        return max(1, (int) $contract->acts()->max('number') + 1);
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

        $data = $request->validate([
            'assignment_id' => $assignmentRule,
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

        if ($fixedAssignment) {
            $data['assignment_id'] = $fixedAssignment->id;
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
