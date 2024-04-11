<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Document\UpdateRequest;
use App\Models\Document;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Document $document)
    {
        $data = $request->validated();
        $document->update($data);

        return redirect()->route('admin.document.show', compact('document'));

    }
}
