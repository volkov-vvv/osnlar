<?php

namespace App\Http\Controllers\Admin\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lid\UpdateRequest;
use App\Models\Lid;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Lid $lid)
    {
        $data = $request->validated();
        $lid->update($data);

        return redirect()->route('admin.lid.show', compact('lid'));

    }
}
