<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Course\UpdateRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Course $course)
    {
        $data = $request->validated();
        $authorIds = $data['author_ids'];
        unset($data['author_ids']);

        $data['prev_img'] = Storage::disk('public')->put('/images', $data['prev_img']);
        $data['image'] = Storage::disk('public')->put('/images', $data['image']);
        $course->update($data);
        $course->authors()->sync($authorIds);
        return redirect()->route('admin.course.show', compact('course'));

    }
}
