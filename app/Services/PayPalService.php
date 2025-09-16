<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));

        $token = $this->provider->getAccessToken();
    }

    public function createOrder($amount, $currency = 'USD')
    {
        try {
        return $this->provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $currency,
                        "value" => number_format($amount, 2, '.', '')
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ]
        ]);
        } catch (\Exception $e) {
            Log::error('PayPal order creation failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function captureOrder($orderId)
    {
        return $this->provider->capturePaymentOrder($orderId);
    }
}
