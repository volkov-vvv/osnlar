<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;

class ShowController extends Controller
{
    public function __invoke(Author $author)
    {
        $courses = $author->courses()
            ->where('is_published', '!=', 0)
            ->get()
            ->sortBy('order');

        $pageTitle = $author->display_name;

        $description = trim(preg_replace('/\s+/', ' ', strip_tags((string) $author->description)));
        $pageDescription = $description !== ''
            ? mb_substr($description, 0, 180)
            : 'Эксперт учебного центра «Основание» — ' . $author->display_name;

        return view('author.show', compact('author', 'courses', 'pageTitle', 'pageDescription'));
    }
}
