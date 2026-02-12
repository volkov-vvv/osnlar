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
        $valArr = [
            'order_id' => 'required|numeric',
        ];
        $needDocs = UserDocument::FIRST_STEP_DOCS;
        foreach ($needDocs as $key => $needDoc){
            $valArr['doc.' . $key] = 'nullable|file|mimes:' . $needDoc['mimes'] . '|max:2048';
        }
        //dump($valArr);
//dd($request);

        $request->validate($valArr);

        $message = '';
        $files = $request->file('doc');

            foreach ($files as $key => $file) {
                $path = $file->store('user_docs', 'public');

                UserDocument::create([
                    'user_id' => Auth::user()->id,
                    'order_id' => $request->order_id,
                    'title' => $file->getClientOriginalName(),
                    'file' => $path,
                    'type' => $key,
                ]);

                $message .= 'Файл загружен: ' . $path . '<br>';
            }




            return $message;


        //return view('user.upload');
    }
}
