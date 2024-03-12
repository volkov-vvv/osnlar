<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Agent\UpdateRequest;
use App\Models\Agent;

use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Agent $agent)
    {
        $data = $request->validated();
        $agent->update($data);

        return redirect()->route('admin.agent.show', compact('agent'));

    }
}
