<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Collator;

class IndexController extends Controller
{
    public function __invoke()
    {
        $collator = class_exists(Collator::class) ? new Collator('ru_RU') : null;

        $authors = Author::all()->sort(function (Author $left, Author $right) use ($collator) {
            if ($collator) {
                return $collator->compare($left->sort_name, $right->sort_name);
            }

            return strcmp(mb_strtolower($left->sort_name), mb_strtolower($right->sort_name));
        })->values();

        $pageTitle = 'Авторы / Эксперты';
        $pageDescription = 'Авторы и эксперты учебного центра «Основание»';

        return view('author.index', compact('authors', 'pageTitle', 'pageDescription'));
    }
}
