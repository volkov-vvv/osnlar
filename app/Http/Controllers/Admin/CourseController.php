<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Course\StoreRequest;
use App\Http\Requests\Admin\Course\UpdateRequest;
use App\Models\Author;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseSeries;
use App\Service\CourseService;

class CourseController extends Controller
{
    public function __construct(private CourseService $service)
    {
    }

    public function index()
    {
        $courses = Course::all();
        $companies = Company::all();

        return view('admin.course.index', compact('courses', 'companies'));
    }

    public function create()
    {
        $authors = Author::all();
        $companies = Company::all();
        return view('admin.course.create', compact('authors', 'companies'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->store($data);

        return redirect()->route('admin.course.index');
    }

    public function show(Course $course)
    {
        $authors = Author::all();

        return view('admin.course.show', compact('course', 'authors'));
    }

    public function edit(Course $course)
    {
        $authors = Author::all();
        $series = CourseSeries::all();
        $companies = Company::all();
        $years = [
            "1" => 2024,
            "2" => 2025,
            "3" => 2026,
        ];
        return view('admin.course.edit', compact('course', 'companies', 'authors','series','years'));
    }

    public function update(UpdateRequest $request, Course $course)
    {
        $data = $request->validated();

        $course = $this->service->update($data, $course);

        return redirect()->route('admin.course.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.course.index');
    }
}
