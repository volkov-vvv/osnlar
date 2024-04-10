<?php

namespace App\Http\Controllers\Admin\About;

use App\Http\Controllers\Controller;

use App\Models\About;

use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(About $about)
    {
        $about->delete();
        return redirect()->route('admin.about.index');

    }
}
