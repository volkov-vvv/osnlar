<?php

namespace App\Http\Controllers\CC\Status;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Status $status)
    {
        $status->delete();
        return redirect()->route('cc.status.index');

    }
}
