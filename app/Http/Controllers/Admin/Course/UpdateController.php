<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Course\UpdateRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Course $course)
    {
        $data = $request->validated();

        $course = $this->service->update($data, $course);

        return redirect()->route('admin.course.show', compact('course'));

    }
}
