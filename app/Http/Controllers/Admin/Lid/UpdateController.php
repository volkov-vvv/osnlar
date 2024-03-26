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
        $oldStatus = $lid->status_id;
        $lid->update($data);
        activity()->performedOn($lid)->withProperties(['status_id_old' => $oldStatus, 'status_id' => $data['status_id']])->log('Изменение статуса');
        return redirect()->route('admin.lid.show', compact('lid'));
    }
}
