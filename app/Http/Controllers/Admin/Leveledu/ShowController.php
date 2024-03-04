<?php

namespace App\Http\Controllers\Admin\Leveledu;

use App\Http\Controllers\Controller;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Leveledu $leveledu)
    {
        return view('admin.leveledu.show', compact('leveledu'));
    }
}
