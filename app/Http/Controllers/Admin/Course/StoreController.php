<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Course\StoreRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        try {
            $data = $request->validated();
            $authorIds = $data['author_ids'];
            unset($data['author_ids']);

            $data['prev_img'] = Storage::disk('public')->put('/images', $data['prev_img']);
            $data['image'] = Storage::disk('public')->put('/images', $data['image']);
            $course = Course::firstOrCreate($data);
            $course->authors()->attach($authorIds);
        }
        catch (\Exception $exception){
            abort(404);
        };

        return redirect()->route('admin.course.index');

    }
}
