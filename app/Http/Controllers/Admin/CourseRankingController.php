<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseRankingController extends Controller
{
    public const CATALOGS = [
        'free' => 'Бесплатные (АМСЗ)',
        'commerce' => 'Платные',
        'future' => 'Код будущего',
        'bioeconomy' => 'Биоэкономика',
    ];

    public function index(Request $request): View
    {
        $years = Year::options();
        $selectedYear = $this->resolveYearFilter($request->get('year'));
        $catalog = $request->get('catalog', 'free');

        if (!array_key_exists($catalog, self::CATALOGS)) {
            $catalog = 'free';
        }

        $courses = $this->filteredCatalogQuery($catalog, $selectedYear)
            ->orderBy('order')
            ->orderBy('title')
            ->get(['id', 'title', 'short_title', 'years', 'order', 'is_published']);

        return view('admin.course.ranking', [
            'years' => $years,
            'selectedYear' => $selectedYear,
            'catalog' => $catalog,
            'catalogs' => self::CATALOGS,
            'courses' => $courses,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'year' => ['nullable', 'string'],
            'catalog' => ['required', 'string', 'in:'.implode(',', array_keys(self::CATALOGS))],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:courses,id'],
        ]);

        $selectedYear = $this->resolveYearFilter($data['year'] ?? null);

        if ($selectedYear !== null && !Year::options()->contains((int) $selectedYear)) {
            return redirect()
                ->route('admin.course.ranking', [
                    'year' => $selectedYear,
                    'catalog' => $data['catalog'],
                ])
                ->with('error', 'Выбранный год недоступен.');
        }

        $allowedIds = $this->filteredCatalogQuery($data['catalog'], $selectedYear)
            ->pluck('id')
            ->all();

        $ids = array_values(array_intersect(
            array_map('intval', $data['ids']),
            $allowedIds
        ));

        $redirectParams = [
            'catalog' => $data['catalog'],
            'year' => $selectedYear ?? 'all',
        ];

        if (count($ids) !== count($data['ids'])) {
            return redirect()
                ->route('admin.course.ranking', $redirectParams)
                ->with('error', 'Список курсов устарел. Обновите страницу и попробуйте снова.');
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Course::whereKey($id)->update([
                    'order' => ($index + 1) * 10,
                ]);
            }
        });

        return redirect()
            ->route('admin.course.ranking', $redirectParams)
            ->with('success', 'Порядок курсов сохранён.');
    }

    private function resolveYearFilter(mixed $year): ?int
    {
        if ($year === null || $year === '' || $year === 'all') {
            return null;
        }

        return (int) $year;
    }

    private function filteredCatalogQuery(string $catalog, ?int $year): Builder
    {
        $query = $this->catalogQuery($catalog);

        if ($year !== null) {
            $query->where('years', $year);
        }

        return $query;
    }

    private function catalogQuery(string $catalog): Builder
    {
        $query = Course::query()->where(function ($q) {
            $q->where('company_id', 1)->orWhereNull('company_id');
        });

        return match ($catalog) {
            'commerce' => $query->whereNotNull('price'),
            'future' => $query->where('code_future', 1),
            'bioeconomy' => $query->where('bioeconomy', 1),
            default => $query
                ->whereNull('price')
                ->where(function ($q) {
                    $q->where('code_future', '!=', 1)->orWhereNull('code_future');
                })
                ->where(function ($q) {
                    $q->where('bioeconomy', '!=', 1)->orWhereNull('bioeconomy');
                }),
        };
    }
}
