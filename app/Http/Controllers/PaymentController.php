<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\Status;
use App\Models\Transaction;
use App\Service\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class PaymentController extends Controller
{
    public function index()
    {
       $transactions = Transaction::all();
       return view('payments.index', ['transactions' => $transactions]);
    }

    public function create(Request $request, PaymentService $service)
    {

        $order_id = (int)$request->input('order_id');
        $amount = (float)$request->input('amount');
        $description = (string)$request->input('description');

        $order = Order::find($order_id);
        $email = $order->user->email;

        $items = array();
        $items[0] = [
            'description' => $order->course->title,
            'quantity' => 1.000,
            'amount' => [
                'value' => $amount,
                'currency' => 'RUB'
            ],
            'vat_code' => 1,
            'payment_mode' => 'full_payment',
            'payment_subject' => 'service',
        ];

//        dump($order_id);
        $transaction = Transaction::create([
            'order_id' => $order_id,
            'amount' => $amount,
            'description' => $description
        ]);
//        dd($transaction);

        if($transaction){
            $link = $service->createPayment($amount, $description, $email, $items, [
                'transaction_id' => $transaction->id
            ]);

            return redirect()->away($link);
        }
    }

    public function callback(Request $request, PaymentService $service)
    {
        $source = file_get_contents('php://input');

        $requestBody = json_decode($source, true);
        $notification = ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
            ? new NotificationSucceeded($requestBody)
            : new NotificationWaitingForCapture($requestBody);

        $payment = $notification->getObject();


        if(isset($payment->status) && $payment->status === 'waiting_for_capture'){
            $service->getClient()->capturePayment([
                'amount' => $payment->amount,
            ], $payment->id, uniqid('', true));
        }


        if(isset($payment->status) && $payment->status === 'succeeded'){
            if((bool)$payment->paid === true){
                $metadata = (object)$payment->metadata;
                if(isset($metadata->transaction_id)){
                    $transactionId = (int)$metadata->transaction_id;
                    $transaction = Transaction::find($transactionId);
                    $transaction->status = PaymentStatusEnum::CONFIRMED;
                    $transaction->save();

                    $order = Order::find($transaction->order_id);
                    $statusPaid = Status::where('code', 'paid')->first();
                    $order->status_id = $statusPaid->id;
                    $order->save();

                    /*
                    if(cache()->has('amount')){
                        cache()->forever('balance', (float)cache()->get('balance') + $payment->amount->value);
                    }else{
                        cache()->forever('balance', (float)$payment->amount->value);
                    }
                    */

                }
            }
        }
    }
}
