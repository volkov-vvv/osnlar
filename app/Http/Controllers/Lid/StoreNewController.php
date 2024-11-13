<?php

namespace App\Http\Controllers\lid;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Lid;
use App\Models\Link;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Lid\StoreNewRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreNewController extends Controller
{
    public function __invoke(StoreNewRequest $request)
    {
        $data = $request->validated();
        // Определение, доступен ли курс в регионе. Пока проверка не нужна
        $links = Link::all()->where('region_id', $request->region_id)->where('course_id', $request->course_id)->last();
        if($links){
            $link = $links->link;
        }else{
            $link = '';
        /*
            $status = Status::all()->where('code', 'not-in-region')->first();
            $data['status_id'] =  $status->id;
        */
        }


        // Всем заявкам присваиваем статус 'На 2025 год'
        $status = Status::all()->where('title', 'На 2025 год')->first();
        $data['status_id'] =  $status->id;

        //Определяем платный курс
        $course = Course::all()->where('id', $request->course_id)->last();
        if(isset($course->price)){ //Если у курса есть цена, то курс платный
            //Проверяем наличие записи в таблице customers. Если записи нет, то создаем
            //$customer = Customer::all()->where('email', $request->email)->last();
            $customer = User::all()->where('email', $request->email)->last();
            if(!$customer){
                $password = Str::random(8);
                /*
                $customerData = array(
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'politic' => 1,
                    'password' => Hash::make($password)
                );
                */

                $customerData = array(
                    'name' => $request->lastname . ' ' . $request->firstname . ' ' . $request->middlename,
                    'email' => $request->email,
                    'role' => 10,
                    'password' => Hash::make($password)
                );

//                $customer = Customer::firstOrCreate($customerData);
                $customer = User::firstOrCreate($customerData);
                dump('Пользователя нет'); //
                dump($customer->id);
                dump('Пароль: ' . $password);
            }
            //Проверяем наличие заказа и создаем, если его нет
            $order = Order::all()->where('customer_id', $customer->id)->last();
            if(!$order){
                $orderData = array(
                    'customer_id' => $customer->id,
                    'course_id' => $request->course_id,
                    'amount' => $course->price,
                    'status' => 'ожидание оплаты'
                );
                $order = Order::firstOrCreate($orderData);
            }else{
                dump('Вы уже оформили заявку на этот курс');
            }
            dump('Номер заказа: ' . $order->id);
            dd('Платный курс ' . $course->price);
        }
        dd('Бесплатный курс ');

/* Временно, чтобы на локалке ошибки не возникало
        $lid = Lid::firstOrCreate($data);
        $data['link'] = $link;
        $data['id'] = $lid->id;
        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.template';
        \Mail::to($data['email'])->send(new SendEmail($mailData));
*/
        return redirect()->route('lid.index');

    }
}
