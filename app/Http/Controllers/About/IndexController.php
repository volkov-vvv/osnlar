<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Course;
use App\Models\Document;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $abouts = About::all();
        $documents = Document::all();
        $pageTitle = "О нас";
        $pageDescription = "Основные сведения о Центре повышения квалификации и профессиональной подготовки «Основание»";
        return view('about.index', compact('abouts','documents','pageTitle', 'pageDescription'));
    }
}
