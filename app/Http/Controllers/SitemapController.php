<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_published', 1)->get();
        return response()->view('sitemap.index',compact('courses'))->header('Content-Type', 'text/xml');;
    }
}
