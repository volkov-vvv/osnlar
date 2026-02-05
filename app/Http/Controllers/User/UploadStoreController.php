<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric',
            'doc' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('doc')) {
            $file = $request->file('doc');
            $path = $file->store('user_docs', 'public');

            UserDocument::create([
                'user_id' => Auth::user()->id,
                'order_id' => $request->order_id,
                'title' => $file->getClientOriginalName(),
                'file' => $path,
            ]);

            return "Файл загружен: " . $path;
        }

        //return view('user.upload');
    }
}
