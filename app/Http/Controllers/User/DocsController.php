<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use function GuzzleHttp\default_user_agent;

class DocsController extends Controller
{
    public function __invoke(Order $order)
    {
        $user = auth()->user();
        $docsCollection = UserDocument::where('user_id', $user->id)->where('order_id', $order->id)->get();
        $needDoc = UserDocument::FIRST_STEP_DOCS;
        $docs = array();
        foreach ($docsCollection as $doc){
            $docs[$doc->type]['title'] = $doc->title;
            $docs[$doc->type]['file'] = $doc->file;
            $docs[$doc->type]['ext'] = pathinfo(storage_path($doc->file), PATHINFO_EXTENSION);
        }
 //       dump($docs->where('type', 'soglasie')->first());
        return view('user.docs', compact('order', 'docs', 'needDoc'));
    }
}
