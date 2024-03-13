<?php

namespace App\Http\Controllers\Admin\Status;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Status\UpdateRequest;
use App\Models\Status;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Status $status)
    {
        $data = $request->validated();
        $status->update($data);

        return redirect()->route('admin.status.show', compact('status'));

    }
}
