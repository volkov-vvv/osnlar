<?php

namespace App\Http\Controllers\CC\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Org\UpdateRequest;
use App\Models\Org;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Org $org)
    {

        $data = $request->validated();
        $oldStatus = $org->status_id;
        $org->update($data);
        $actionDescription = 'Изменение информации';
        if ($oldStatus != $data['status_id']) $actionDescription = 'Изменение статуса';
        activity()->performedOn($org)->withProperties(['status_id_old' => $oldStatus, 'status_id' => $data['status_id'], 'comment' => $request->comment])->log($actionDescription);

        return redirect()->route('cc.org.show', compact('org'));

    }
}
