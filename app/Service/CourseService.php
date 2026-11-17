<?php

namespace App\Service;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CourseService
{
    public function __construct(private ImageOptimizer $images)
    {
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            if (isset($data['author_ids'])) {
                $authorIds = $data['author_ids'];
                unset($data['author_ids']);
            }

            $data['prev_img'] = $this->images->storeCoursePreview($data['prev_img']);
            $data['image'] = $this->images->storeCourseDetail($data['image']);
            if (isset($data['utp'])) {
                $data['utp'] = Storage::disk('public')->put('/utps', $data['utp']);
            }

            $course = Course::firstOrCreate($data);
            if (isset($authorIds)) {
                $course->authors()->attach($authorIds);
            }

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            report($exception);

            throw $exception;
        }
    }

    public function update($data, $course)
    {
        $oldPrevImg = null;
        $oldImage = null;

        try {
            DB::beginTransaction();
            if (isset($data['author_ids'])) {
                $authorIds = $data['author_ids'];
                unset($data['author_ids']);
            }

            if (isset($data['prev_img'])) {
                $oldPrevImg = $course->prev_img;
                $data['prev_img'] = $this->images->storeCoursePreview($data['prev_img']);
            }
            if (isset($data['image'])) {
                $oldImage = $course->image;
                $data['image'] = $this->images->storeCourseDetail($data['image']);
            }
            if (isset($data['utp'])) {
                $data['utp'] = Storage::disk('public')->put('/utps', $data['utp']);
            }

            $course->update($data);
            if (isset($authorIds)) {
                $course->authors()->sync($authorIds);
            }
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            report($exception);

            throw $exception;
        }

        $this->images->deleteIfExists($oldPrevImg);
        $this->images->deleteIfExists($oldImage);

        return $course;
    }
}
