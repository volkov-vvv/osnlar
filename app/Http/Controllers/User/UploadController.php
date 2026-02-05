<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use function GuzzleHttp\default_user_agent;

class UploadController extends Controller
{
    public function __invoke(Order $order)
    {

        return view('user.upload', compact('order'));
    }
}
