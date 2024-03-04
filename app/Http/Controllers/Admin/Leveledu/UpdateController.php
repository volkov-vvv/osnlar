<?php

namespace App\Http\Controllers\Admin\Leveledu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Leveledu\UpdateRequest;
use App\Models\Leveledu;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Leveledu $leveledu)
    {
        $data = $request->validated();
        $leveledu->update($data);

        return redirect()->route('admin.leveledu.show', compact('leveledu'));

    }
}
