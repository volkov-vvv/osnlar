<?php

namespace App\Http\Controllers\Admin\Leveledu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Leveledu $leveledu)
    {
        $leveledu->delete();
        return redirect()->route('admin.leveledu.index');

    }
}
