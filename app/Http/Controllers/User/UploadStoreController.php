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
            'action' => 'required|string',
        ];
        $needDocs = UserDocument::FIRST_STEP_DOCS;
        foreach ($needDocs as $key => $needDoc){
            $valArr['doc.' . $key] = 'nullable|file|mimes:' . $needDoc['mimes'] . '|max:2048';
        }
        //dump($valArr);
//dd($request);



        $request->validate($valArr);

        switch ($request->action) {
            case 'save':
                $message = '';
                $files = $request->file('doc');

                if(!empty($files)){
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
                }
                return $message;
            case 'first_step_done':

                $docsCollection = UserDocument::where('user_id', Auth::user()->id)->where('order_id', $request->order_id)->get();
                dump($docsCollection);

                foreach ($needDocs as $key => $needDoc){

                    if($docsCollection->doesntContain('type', $key)) echo $needDoc['title'] . ' не загружен<br>';
                }

 //               echo "Проверяем, все ли документы загружены";
                break;
        }




        //return view('user.upload');
    }
}
