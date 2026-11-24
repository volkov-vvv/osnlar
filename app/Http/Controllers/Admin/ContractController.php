<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesScannedPdf;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Contract;
use App\Models\ContractWorkflowStatus;
use App\Models\Course;
use App\Models\Project;
use App\Service\ContractActivityLogger;
use App\Service\ContractDocumentService;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
    use ManagesScannedPdf;

    public function index()
    {
        $contracts = Contract::query()
            ->with(['author', 'course', 'project', 'assignments', 'reports', 'acts'])
            ->latest()
            ->get();

        $authors = $this->authorsList();
        $courses = $this->coursesList();
        $projects = $this->projectsList();

        return view('admin.contract.index', compact('contracts', 'authors', 'courses', 'projects'));
    }

    public function create()
    {
        $templates = $this->templatesList();
        $projects = $this->projectsList();
        $authors = $this->authorsList();
        $courses = $this->coursesList();

        return view('admin.contract.create', compact('templates', 'projects', 'authors', 'courses'));
    }

    public function store(Request $request, ContractActivityLogger $activityLogger)
    {
        $data = $this->validatedContractData($request);

        $contract = Contract::create($data);
        $contract->assignNumber();
        $activityLogger->created($contract);

        return redirect()
            ->route('admin.contract.index')
            ->with('success', 'Договор сохранён');
    }

    public function show(Contract $contract, ContractActivityLogger $activityLogger)
    {
        $contract->load([
            'author',
            'course',
            'project',
            'assignments.studyGroup',
            'assignments.report',
            'assignments.perStudentReport',
            'assignments.acts',
            'assignments.perStudentAct',
            'reports.assignment',
            'acts.assignment',
        ]);

        $activities = $activityLogger->historyForContract($contract);

        return view('admin.contract.show', compact('contract', 'activities'));
    }

    public function edit(Contract $contract)
    {
        $templates = $this->templatesList();
        $projects = $this->projectsList();
        $authors = $this->authorsList();
        $courses = $this->coursesList();

        return view('admin.contract.edit', compact('contract', 'templates', 'projects', 'authors', 'courses'));
    }

    public function update(Request $request, Contract $contract, ContractActivityLogger $activityLogger)
    {
        $data = $this->validatedContractData($request, $contract);
        $before = $activityLogger->snapshot($contract);

        $contract->update($data);
        $activityLogger->updated($contract, $before, $activityLogger->snapshot($contract->fresh()));

        return redirect()
            ->route('admin.contract.show', $contract)
            ->with('success', 'Договор обновлён');
    }

    public function generate(Contract $contract)
    {
        $view = $contract->template_view;
        if (! $view) {
            return redirect()
                ->route('admin.contract.index')
                ->withErrors(['template' => 'Шаблон договора не найден']);
        }

        $contract->loadMissing(['author', 'course', 'project']);
        $author = $contract->author;
        $filename = 'dogovor-'.$contract->template.'-'.$author->id;
        $nameSlug = Str::slug($author->full_name ?: $author->name);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        /** @var DomPdfWrapper $pdf */
        $pdf = app('dompdf.wrapper');
        $this->registerContractFonts($pdf);

        $pdf->loadView($view, [
            'author' => $author,
            'course' => $contract->course,
            'project' => $contract->project,
            'contract' => $contract,
        ])->setPaper('a4');

        $this->addCenteredPageNumbers($pdf);

        return $pdf->download($filename.'.pdf');
    }

    public function generateDocx(Contract $contract, ContractDocumentService $documentService)
    {
        if (! $contract->template_view) {
            return redirect()
                ->route('admin.contract.show', $contract)
                ->withErrors(['template' => 'Шаблон договора не найден']);
        }

        $document = $documentService->generate($contract);

        return response($document['binary'], 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="'.$document['filename'].'"',
        ]);
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

    public function destroy(Contract $contract, ContractActivityLogger $activityLogger)
    {
        $activityLogger->deleted($contract);
        $contract->delete();

        return redirect()
            ->route('admin.contract.index')
            ->with('success', 'Договор удалён');
    }

    public function uploadScan(Request $request, Contract $contract)
    {
        return $this->uploadScannedPdf(
            $request,
            $contract,
            'contracts/scanned/contracts',
            'admin.contract.show',
            $contract
        );
    }

    public function downloadScan(Contract $contract)
    {
        return $this->downloadScannedPdf(
            $contract,
            $contract->scannedPdfDownloadName('skan-dogovor-'.Str::slug((string) $contract->number))
        );
    }

    public function destroyScan(Contract $contract)
    {
        return $this->destroyScannedPdf($contract, 'admin.contract.show', $contract);
    }

    private function validatedContractData(Request $request, ?Contract $contract = null): array
    {
        $templateSlugs = array_keys(config('contracts.templates', []));

        $rules = [
            'template' => ['required', 'string', Rule::in($templateSlugs)],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'status' => ['required', 'string', Rule::in(ContractWorkflowStatus::keys())],
            'signed_at' => ['nullable', 'date'],
            'nap_registered_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:signed_at'],
        ];

        $messages = [
            'template.required' => 'Выберите шаблон договора',
            'template.in' => 'Выбранный шаблон не найден',
            'project_id.required' => 'Выберите проект',
            'project_id.exists' => 'Выбранный проект не найден',
            'author_id.required' => 'Выберите автора',
            'author_id.exists' => 'Выбранный автор не найден',
            'course_id.required' => 'Выберите курс',
            'course_id.exists' => 'Выбранный курс не найден',
            'status.required' => 'Выберите статус',
            'status.in' => 'Выбран недопустимый статус',
            'signed_at.date' => 'Укажите корректную дату подписания',
            'nap_registered_at.date' => 'Укажите корректную дату регистрации плательщика НПД',
            'ends_at.date' => 'Укажите корректную дату окончания договора',
            'ends_at.after_or_equal' => 'Дата окончания не может быть раньше даты подписания',
            'number.required' => 'Укажите номер договора',
            'number.unique' => 'Договор с таким номером уже существует',
        ];

        if ($contract) {
            $rules['number'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('contracts', 'number')
                    ->ignore($contract->id)
                    ->whereNull('deleted_at'),
            ];
        }

        $data = $request->validate($rules, $messages);

        foreach (['signed_at', 'nap_registered_at', 'ends_at'] as $dateField) {
            if (($data[$dateField] ?? null) === '') {
                $data[$dateField] = null;
            }
        }

        if (array_key_exists('number', $data)) {
            $data['number'] = trim($data['number']);
        }

        return $data;
    }

    private function templatesList()
    {
        return collect(config('contracts.templates', []))
            ->map(fn (array $template, string $slug) => [
                'slug' => $slug,
                'title' => $template['title'] ?? $slug,
            ])
            ->values();
    }

    private function projectsList()
    {
        return Project::query()
            ->orderBy('id')
            ->get();
    }

    private function authorsList()
    {
        return Author::query()
            ->orderByRaw("COALESCE(NULLIF(TRIM(lastname), ''), name) ASC")
            ->orderBy('firstname')
            ->orderBy('middlename')
            ->get();
    }

    private function coursesList()
    {
        return Course::query()
            ->orderBy('title')
            ->get();
    }
}
