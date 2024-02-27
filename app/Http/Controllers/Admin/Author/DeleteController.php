<?php

namespace App\Http\Controllers\Admin\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Author $author)
    {
        $author->delete();
        return redirect()->route('admin.author.index');

    }
}
