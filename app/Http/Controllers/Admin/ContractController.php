<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Contract;
use App\Models\Course;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::query()
            ->with(['author', 'course'])
            ->latest()
            ->get();

        $authors = $this->authorsList();
        $courses = $this->coursesList();

        return view('admin.contract.index', compact('contracts', 'authors', 'courses'));
    }

    public function create()
    {
        $templates = $this->templatesList();
        $authors = $this->authorsList();
        $courses = $this->coursesList();

        return view('admin.contract.create', compact('templates', 'authors', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedContractData($request);

        $contract = Contract::create($data);
        $contract->assignNumber();

        return redirect()
            ->route('admin.contract.index')
            ->with('success', 'Договор сохранён');
    }

    public function show(Contract $contract)
    {
        $contract->load(['author', 'course']);

        return view('admin.contract.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $templates = $this->templatesList();
        $authors = $this->authorsList();
        $courses = $this->coursesList();

        return view('admin.contract.edit', compact('contract', 'templates', 'authors', 'courses'));
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $this->validatedContractData($request);

        $contract->update($data);

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

        $contract->loadMissing(['author', 'course']);
        $author = $contract->author;
        $filename = 'dogovor-'.$contract->template.'-'.$author->id;
        $nameSlug = Str::slug($author->full_name ?: $author->name);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        /** @var DomPdfWrapper $pdf */
        $pdf = app('dompdf.wrapper');
        $this->registerContractFonts($pdf);

        return $pdf->loadView($view, [
            'author' => $author,
            'course' => $contract->course,
            'contract' => $contract,
        ])
            ->setPaper('a4')
            ->download($filename.'.pdf');
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

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()
            ->route('admin.contract.index')
            ->with('success', 'Договор удалён');
    }

    private function validatedContractData(Request $request): array
    {
        $templateSlugs = array_keys(config('contracts.templates', []));

        $data = $request->validate([
            'template' => ['required', 'string', Rule::in($templateSlugs)],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'signed_at' => ['nullable', 'date'],
            'nap_registered_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:signed_at'],
        ], [
            'template.required' => 'Выберите шаблон договора',
            'template.in' => 'Выбранный шаблон не найден',
            'author_id.required' => 'Выберите автора',
            'author_id.exists' => 'Выбранный автор не найден',
            'course_id.required' => 'Выберите курс',
            'course_id.exists' => 'Выбранный курс не найден',
            'signed_at.date' => 'Укажите корректную дату подписания',
            'nap_registered_at.date' => 'Укажите корректную дату регистрации плательщика НПД',
            'ends_at.date' => 'Укажите корректную дату окончания договора',
            'ends_at.after_or_equal' => 'Дата окончания не может быть раньше даты подписания',
        ]);

        foreach (['signed_at', 'nap_registered_at', 'ends_at'] as $dateField) {
            if (($data[$dateField] ?? null) === '') {
                $data[$dateField] = null;
            }
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
