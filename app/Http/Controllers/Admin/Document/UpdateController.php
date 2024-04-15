<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Document\UpdateRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Document $document)
    {
        $data = $request->validated();
        if (isset($data['file'])){
            $data['file'] = Storage::disk('public')->put('/documents', $data['file']);
        }

        $document->update($data);

        return redirect()->route('admin.document.show', compact('document'));

    }
}
