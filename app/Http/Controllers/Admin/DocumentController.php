<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Document\StoreRequest;
use App\Http\Requests\Admin\Document\UpdateRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('admin.document.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.document.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $data['file'] = Storage::disk('public')->putFile('/documents/', $data['file']);

        Document::firstOrCreate($data);

        return redirect()->route('admin.document.index');
    }

    public function show(Document $document)
    {
        return view('admin.document.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('admin.document.edit', compact('document'));
    }

    public function update(UpdateRequest $request, Document $document)
    {
        $data = $request->validated();
        if (isset($data['file'])){
            $data['file'] = Storage::disk('public')->put('/documents', $data['file']);
        }

        $document->update($data);

        return redirect()->route('admin.document.show', compact('document'));
    }

    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('admin.document.index');
    }
}
