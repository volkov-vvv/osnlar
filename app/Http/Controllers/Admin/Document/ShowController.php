<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Document $document)
    {
        return view('admin.document.show', compact('document'));
    }
}
