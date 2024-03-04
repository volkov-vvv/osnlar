<?php

namespace App\Http\Controllers\Admin\Leveledu;

use App\Http\Controllers\Controller;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Leveledu $leveledu)
    {
        return view('admin.leveledu.edit', compact('leveledu'));
    }
}
