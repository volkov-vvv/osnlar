<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\Course;
use App\Models\Org;

class CreateBioeconomyPersonalController extends Controller
{
    public function __invoke(Course $course)
    {
        if ((int) $course->is_published !== 1 || (int) $course->bioeconomy !== 1) {
            abort(404);
        }

        $pageTitle = 'Персональная запись на курс по Биоэкономике';
        $pageDescription = 'Персональная запись на курс «'.$course->title.'» в рамках национального проекта «Технологическое обеспечение биоэкономики»';
        $genders = BioLid::GENDERS;
        $organizations = Org::query()
            ->where('source', 'bioeconomy')
            ->whereNotNull('organization_title')
            ->where('organization_title', '!=', '')
            ->orderByDesc('id')
            ->get(['id', 'organization_title', 'inn'])
            ->unique(function (Org $org) {
                return mb_strtolower(trim($org->organization_title));
            })
            ->sortBy(function (Org $org) {
                return mb_strtolower($org->organization_title);
            }, SORT_NATURAL)
            ->values();

        return view('lid.create_bioeconomy_personal', compact(
            'course',
            'genders',
            'organizations',
            'pageTitle',
            'pageDescription'
        ));
    }
}
