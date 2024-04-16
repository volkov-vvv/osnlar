<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Document\StoreRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        dump($data);

//        $data['file'] = Storage::disk('public')->putFile('/documents/'.$data['file'], $request->file('file'));
        $data['file'] = Storage::disk('public')->putFile('/documents/', $data['file']);

        Document::firstOrCreate($data);
dd('!!!');
        return redirect()->route('admin.document.index');

    }
}
