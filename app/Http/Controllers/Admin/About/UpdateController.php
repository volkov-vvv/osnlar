<?php

namespace App\Http\Controllers\Admin\About;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\About\UpdateRequest;
use App\Models\About;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, About $about)
    {
        $data = $request->validated();
        $about->update($data);

        return redirect()->route('admin.about.show', compact('about'));

    }
}
