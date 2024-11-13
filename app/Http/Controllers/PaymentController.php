<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Order;
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


        $transaction = Transaction::create([
            'order_id' => $order_id,
            'amount' => $amount,
            'description' => $description
        ]);

        if($transaction){
            $link = $service->createPayment($amount, $description, [
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
                    $order->status = 'оплачен';
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
