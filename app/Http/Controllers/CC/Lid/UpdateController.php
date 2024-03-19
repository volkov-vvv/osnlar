<?php

namespace App\Http\Controllers\CC\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Lid\UpdateRequest;
use App\Models\Lid;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Lid $lid)
    {
        $data = $request->validated();
        $lid->update($data);

        return redirect()->route('cc.lid.show', compact('lid'));

    }
}
