<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Course;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_published', 1)->get();
        $authors = Author::published()->orderBy('id')->get();
        return response()->view('sitemap.index',compact('courses', 'authors'))->header('Content-Type', 'text/xml');
    }
}
