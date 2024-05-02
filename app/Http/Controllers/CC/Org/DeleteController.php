<?php

namespace App\Http\Controllers\CC\Org;

use App\Http\Controllers\Controller;

use App\Models\Org;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Org $org)
    {
        $org->delete();
        return redirect()->route('cc.org.index');

    }
}
