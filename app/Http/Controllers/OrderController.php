<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\Common\Order\StoreRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function create(Course $course)
    {
        $pageDescription = 'Заказ на обучение';
        return view('order.create', compact('pageDescription', 'course'));
    }

    public function store(StoreRequest $request)
    {
        $pageDescription = 'Заказ на обучение';
        $data = $request->validated();

        $course = Course::all()->where('id', $data['course_id'])->last();
        $customer = User::all()->where('email', $data['email'])->last();
        if(!$customer){
            $password = Str::random(8);


            $customerData = array(
                'name' => $data['lastname'] . ' ' . $data['firstname'] . ' ' . $data['middlename'],
                'email' => $data['email'],
                'role' => 10,
                'password' => Hash::make($password)
            );

            $customer = User::firstOrCreate($customerData);
            $userData = [
                'email' => $data['email'],
                'password' => $password,
            ];
        }else{
            dd('Пользователь уже существует'); //Временно
        }

        //Проверяем наличие заказа и создаем, если его нет
        $order = Order::all()->where('customer_id', $customer->id)->where('course_id', $course->id)->last();
        if(!$order){
            $orderData = array(
                'customer_id' => $customer->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'status' => 'ожидание оплаты'
            );
            $order = Order::firstOrCreate($orderData);
        }else{
            dd('Вы уже оформили заявку на этот курс'); //Временно
        }
        return view('order.store', compact('userData', 'course', 'pageDescription'));
    }
}
