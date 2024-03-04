<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Models\Lid;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Lid $lid)
    {
        $lid>delete();
        return redirect()->route('admin.lid.index');

    }
}
