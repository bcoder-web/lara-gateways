<?php

namespace Betacoders\Gateway\services\stripe;

use Betacoders\Gateway\interfaces\GatewayServiceInterface;
use InvalidArgumentException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class Stripe implements GatewayServiceInterface
{
    protected $amount = 0.0;
    public string $currency = 'usd';
    private string $api_key = '';
    private string $secret_key = '';
    public string $customer = ''; //cus_N7r8lwykCJHxwS
    protected string $source = '';
    protected object $stripe;

    public function __construct()
    {
        $this->api_key = env('STRIPE_PUBLISHABLE_KEY');
        $this->secret_key = env('STRIPE_SECRET_KEY');
        $this->currency = env('STRIPE_CURRENCY');

        if (empty($this->api_key) && empty($this->secret_key) && !empty($this->currency)){
            throw (new InvalidArgumentException("Please check your stripe credentials"));
        }
        $this->stripe = new StripeClient($this->secret_key);
    }

    /**
     * @throws ApiErrorException
     * @throws \Throwable
     * @param $data = additional data form charge api of stripe
     */
    public function charge($data = []): \Stripe\ApiResponse|\Stripe\ErrorObject|array
    {
        if (empty($this->customer) && empty($this->source)){
            throw (new InvalidArgumentException("Set a customer by setCustomer() method or source by setSource()"));
        }
        if (empty($this->amount)){
            throw (new InvalidArgumentException("Set chargeable amount by setAmount()"));
        }
        if (isset($data['intent']) && $data['intent']){
            $response = (new Charge($this->bindData()))->create($data);
        }else{
            $response = (new Charge($this->bindData()))->create($data);
        }


        return $response;
    }

    private function bindData(): array
    {
        return [
            'amount'=>$this->amount,
            'currency'=>$this->currency,
            'customer'=>$this->customer,
            'source'=>$this->source,
        ];
    }

    public function setSource(string $source): void{
        $this->source = $source;
    }
    public function getSource(): string
    {
        return $this->source;
    }
    public function setCustomer(string $customerId): void{
        $this->customer = $customerId;
    }
    public function getCustomer(): string
    {
        return $this->customer;
    }
    public function setAmount($amount): void
    {
        $total = (float)$amount;
        if (is_numeric($amount)){
            $this->amount = $amount * 100;
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
