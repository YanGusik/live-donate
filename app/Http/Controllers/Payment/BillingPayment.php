<?php

namespace App\Http\Controllers\Payment;


class BillingPayment
{

    private $gateway;
    private $payment = [];
    private $isRedirect = false;
    private $isSuccessful = false;
    private $message = "";

    public function create(string $gateway): BillingPayment
    {
        if (!in_array($gateway, PaymentType::TYPES))
            throw new \Exception("Gateway not found");

        $this->gateway = $gateway;
        return $this;
    }

    public function purchase(array $payment): BillingPayment
    {
        $this->payment = $payment;
        return $this;
    }

    public function send(): BillingPayment
    {
        if ($this->isRedirect == true)
            $this->isSuccessful = true;
        else if ($this->gateway == PaymentType::PAYPAL)
            $this->isRedirect = true;
        else
            $this->isSuccessful = true;
        return $this;
    }

    public function completePurchase(string $paymentId): BillingPayment
    {
        $this->gateway = PaymentType::PAYPAL;
        $this->isRedirect = true;
        return $this;
    }

    public function isRedirect(): bool
    {
        return $this->isRedirect;
    }

    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    public function getPayment(): array
    {
        return [
            'id' => '1',
        ];
    }

    public function getRedirect(): array
    {
        return [
            'status' => 'redirect',
            'url' => 'paypal.ru'
        ];
    }

    public function getMessage(): array
    {
        return [
            'status' => 'success',
            'message' => 'success message'
        ];
    }

    public function getError(): array
    {
        return [
            'status' => 'error',
            'message' => 'error connection'
        ];
    }

}
