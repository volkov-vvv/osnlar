<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\Agent;
use App\Models\Course;
use App\Http\Requests\Common\Order\StoreRequest;
use App\Models\Order;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function create(Request $request, Course $course)
    {
        $pageDescription = 'Заказ на обучение';
        $regions = Region::all();
        $agents = Agent::where('active', 1)->get();

        $cookies = $request->cookie();
        $utm = ['utm_source' => '', 'utm_medium' => '', 'utm_campaign' =>''];

        if(isset($_GET['utm_source'])){
            $utm['utm_source'] = $_GET['utm_source'];
        }
        elseif(isset($cookies['utm_source'])){
            $utm['utm_source'] = $cookies['utm_source'];
        }

        if(isset($_GET['utm_medium'])){
            $utm['utm_medium'] = $_GET['utm_medium'];
        }
        elseif(isset($cookies['utm_medium'])){
            $utm['utm_medium'] = $cookies['utm_medium'];
        }

        if(isset($_GET['utm_campaign'])){
            $utm['utm_campaign'] = $_GET['utm_campaign'];
        }
        elseif(isset($cookies['utm_campaign'])){
            $utm['utm_campaign'] = $cookies['utm_campaign'];
        }

        return view('order.create', compact('pageDescription', 'course', 'regions', 'agents', 'utm'));
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
                'name' =>  $data['firstname'],
                'middlename' => $data['middlename'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone_prefix' => $data['phone_prefix'],
                'phone' => explode('+' . $data['phone_prefix'], $data['phone'])[1],
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

            $status = Status::where('code', 'waiting-payment')->first();
            $orderData = array(
                'customer_id' => $customer->id,
                'course_id' => $course->id,
                'region_id' => $data['region_id'],
                'amount' => $course->price,
                'status_id' => $status->id,
                'agent_id' => $data['agent_id'],
                'utm_source' => $data['utm_source'],
                'utm_medium' => $data['utm_medium'],
                'utm_campaign' => $data['utm_campaign'],
            );

            $order = Order::firstOrCreate($orderData);
        }else{
            dd('Вы уже оформили заявку на этот курс'); //Временно
        }
        //Отправка письма пользователю
        $data['login'] = $userData['email'];
        $data['password'] = $userData['password'];
        $data['course_title'] = $course->title;
        $data['order_number'] = $order->id;
        $data['region'] = $order->region->title;
        $data['price'] = $course->price;
        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.order';
        \Mail::to($data['email'])->send(new SendEmail($mailData));

        //Отправка письма сотруднику
        $emails = [
            'mitin_a@mail.ru',
            'nik.swet.83@mail.ru',
            'Obr@osnovanie.info'
        ];
        $mailData->subject = 'Создан новый заказ на обучение №' . $order->id;
        $mailData->template = 'mails.order_employee';
        foreach ($emails as $email) {
            \Mail::to($email)->send(new SendEmail($mailData));
        }

        return view('order.store', compact('userData', 'course', 'pageDescription'));
    }
}
