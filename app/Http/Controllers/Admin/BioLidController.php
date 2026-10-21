<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BioLid;
use App\Models\Course;

class BioLidController extends Controller
{
    public function index()
    {
        $bioLids = BioLid::query()
            ->with('course')
            ->latest()
            ->get();

        $courses = Course::query()
            ->where('bioeconomy', 1)
            ->orderBy('title')
            ->get();

        $organizations = BioLid::query()
            ->whereNotNull('organization_title')
            ->where('organization_title', '!=', '')
            ->distinct()
            ->orderBy('organization_title')
            ->pluck('organization_title');

        return view('admin.biolid.index', compact('bioLids', 'courses', 'organizations'));
    }

    public function show(BioLid $biolid)
    {
        $biolid->load('course');

        return view('admin.biolid.show', ['bioLid' => $biolid]);
    }

    public function destroy(BioLid $biolid)
    {
        $biolid->delete();

        return redirect()
            ->route('admin.biolid.index')
            ->with('success', 'Заявка удалена');
    }
}
