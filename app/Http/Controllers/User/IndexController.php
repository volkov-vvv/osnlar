<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;


class IndexController extends Controller
{
    public function __invoke()    {
        $currentUserId = auth()->user()->id;
        $userOrders = Order::all()->where('customer_id', $currentUserId);

        dump($userOrders);
        return view('user.index', compact('userOrders'));
    }
}
