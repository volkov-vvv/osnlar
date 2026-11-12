<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lid\BioeconomyPersonalStoreRequest;
use App\Models\BioLid;
use App\Models\Course;
use App\Service\BioLidAutomationService;

class StoreBioeconomyPersonalController extends Controller
{
    public function __construct(private BioLidAutomationService $automation)
    {
    }

    public function __invoke(BioeconomyPersonalStoreRequest $request)
    {
        $data = $request->safe()->except(['org_id', 'phone_prefix', 'politic']);
        $data['politic'] = true;

        $course = Course::query()->findOrFail($data['course_id']);
        $bioLid = BioLid::create($data);

        $this->automation->processAfterCreate($bioLid, $course);

        return redirect()->route('lid.index');
    }
}
