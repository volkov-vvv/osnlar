<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\Common\Order\UpdateRequest;
use App\Models\Status;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return view('admin.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $statuses = Status::where('type', 'order')->get();
        $users = User::where('role', 3)->get();
        return view('admin.order.edit', compact('order', 'statuses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Order $order)
    {
        $data = $request->validated();
        $oldStatus = $order->status_id;
        $order->update($data);
        $actionDescription = 'Изменение информации';
        if ($oldStatus != $data['status_id']) $actionDescription = 'Изменение статуса';
        activity('order')->performedOn($order)->withProperties(['status_id_old' => $oldStatus, 'status_id' => $data['status_id'], 'comment' => $request->comment])
            ->log($actionDescription);
        dd(['status_id_old' => $oldStatus, 'status_id' => $data['status_id'], 'comment' => $request->comment]);

        return redirect()->route('admin.order.edit', compact('order'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
