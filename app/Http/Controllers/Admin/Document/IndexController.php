<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $documents = Document::all();
        return view('admin.document.index', compact('documents'));
    }
}
