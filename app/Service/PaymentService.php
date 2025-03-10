<?php

namespace App\Service;

use YooKassa\Client;

class PaymentService
{
    public function getClient(): Client
    {
        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret_key'));

        return $client;
    }

    public function createPayment(float $amount, string $description, string $email, array $items, array $options = [])
    {
        $client = $this->getClient();
        $payment = $client->createPayment([
            'amount' => [
                'value' => $amount,
                'currency' => 'RUB',
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('user.index'),
            ],
            'metadata' => [
                'transaction_id' => $options['transaction_id'],
            ],
            'description' => $description,
            'receipt' => [
                'customer' => [
                    'email' => $email,
                ],
                'items' => $items,
            ]
        ],  uniqid('', true));

        return $payment->getConfirmation()->getConfirmationUrl();
    }
}
