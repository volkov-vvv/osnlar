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
        $data = $request->validated();
        $data['prev_img'] = Storage::put('/images', $data['prev_img']);
        $data['image'] = Storage::put('/images', $data['image']);
        Course::firstOrCreate($data);
        return redirect()->route('admin.course.index');

    }
}
