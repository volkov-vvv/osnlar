<?php

namespace App\Http\Controllers\Admin\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $authors = Author::all();
        return view('admin.author.index', compact('authors'));
    }
}
