<?php

namespace App\Http\Controllers\Admin\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Author $author)
    {
        return view('admin.author.show', compact('author'));
    }
}
