<?php

namespace Betacoders\Gateway\services\stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class Intent extends Stripe
{
    public function __construct($data) {
        parent::__construct();
        if (!empty($data["amount"]))
            $this->setAmount($data["amount"]);
        if (!empty($data["source"]))
            $this->setSource($data["source"]);
        if (!empty($data["customer"]))
            $this->setCustomer($data["customer"]);
        if (!empty($data["currency"]))
            $this->setCurrency($data["currency"]);
    }

    /**
     * @throws ApiErrorException
     */
    public function create($data = []){
        try {
            $intentData = [
                "amount"=>$this->amount,
                "currency"=>$this->currency
            ];
            if (!empty($data["payment_method_types"]) && is_array($data["payment_method_types"])){
                $intentData["payment_method_types"] = $data["payment_method_types"];
            }else{
                $intentData["automatic_payment_methods"] = ["enabled"=>true];
            }

            if (isset($data['confirm']) && is_bool($data['confirm'])){
                $intentData['confirm'] = $data['confirm'];
                if ($intentData['confirm'] && isset($data['off_session']) && is_bool($data['off_session'])){
                    $intentData["off_session"] = $data['off_session'];
                }
            }

            if (isset($data['metadata']) && is_array($data['metadata'])){
                $intentData['metadata'] = $data['metadata'];
            }

            if (isset($data['description'])){
                $intentData['description'] = $data['description'];
            }
            if (isset($data['payment_method'])){
                $intentData['payment_method'] = $data['payment_method'];
            }
            if (isset($data['receipt_email'])){
                $intentData['receipt_email'] = $data['receipt_email'];
            }
            if (!empty($data['capture_method'])){
                $intentData['capture_method'] = $data['capture_method'];
            }
            return PaymentIntent::create($intentData)->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

    public function capture($intentId){
        try {
           $intent = PaymentIntent::retrieve($intentId);
            $intent = $intent->capture();
            return $intent->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

    public function cancel($intentId){
        try {
            $intent = PaymentIntent::retrieve($intentId);
            $intent = $intent->cancel();
            return $intent->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

}
