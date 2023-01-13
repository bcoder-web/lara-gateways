<?php

namespace Betacoders\Gateway\services\stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;

class Charge extends Stripe
{
    public function __construct($data) {
        parent::__construct();
        if (!empty($data['amount']))
            $this->setAmount($data['amount']);
        if (!empty($data['source']))
            $this->setSource($data['source']);
        if (!empty($data['customer']))
            $this->setCustomer($data['customer']);
        if (!empty($data['currency']))
            $this->setCurrency($data['currency']);
    }
    /**
     * @param $data
     * @return \Stripe\ApiResponse|\Stripe\ErrorObject|null|array
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function create(array $data=[]): \Stripe\ApiResponse|\Stripe\ErrorObject|null|array{

        try {
            $chargeData = [
                'amount'=>$this->amount,
                'currency'=>$this->currency,
                'description'=>$data['description']??'',
                'metadata'=>$data['metadata']??[],
            ];
            if (!empty($this->source)){
                $chargeData['source'] = $this->source;
            }
            if (!empty($this->customer)){
                $chargeData['customer'] = $this->customer;
            }

            if (!empty($data['shipping']) && !empty($data['shipping']['address']) && !empty($data['shipping']['name'])){
                $chargeData['shipping'] = $data['shipping'];
            }

            if (!empty($data['transfer_data']) && !empty($data['transfer_data']['destination'])){
                $chargeData['transfer_data'] = $data['transfer_data'];
            }
            if (!empty($data['receipt_email'])){
                $chargeData['receipt_email'] = $data['receipt_email'];
            }
            $chargeData['capture'] = $data['capture']??true;

            $response = $this->stripe->charges->create($chargeData);
            return $response->jsonSerialize();
        }catch (CardException $cardException){
            return $cardException->getError();
        }
    }

    /**
     * @param string $id
     * @param array $data
     * @return \Stripe\ApiResponse|\Stripe\ErrorObject|null
     * @throws ApiErrorException
     */
    public function update(string $id, array $data = []): \Stripe\ApiResponse|\Stripe\ErrorObject|null{
        try {
            $chargeData = [];

            if (!empty($this->customer)){
                $chargeData['customer'] = $this->customer;
            }

            if (!empty($data['shipping']) && !empty($data['shipping']['address']) && !empty($data['shipping']['name'])){
                $chargeData['shipping'] = $data['shipping'];
            }
            if (!empty($data['metadata'])){
                $chargeData['metadata'] = $data['metadata'];
            }
            if (!empty($chargeData)){
                $response = $this->stripe->charges->update($id, $chargeData);
                return $response->jsonSerialize();
            }

        }catch (CardException $cardException){
            return $cardException->getError();
        }
    }

    /**
     * @param string $id
     * @return array|mixed|string
     */
    public function retrive(string $id): mixed
    {
        try {
            $charge = $this->stripe->charges->retrieve($id);
            return $charge->jsonSerialize();
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param string $id
     * @param array $data
     * @return string|void
     */
    public function capture(string $id, array $data=[]){
        try {
            $captureData = [];
            if ($data['amount']){
                $captureData['amount'] = $data['amount'];
            }
            if ($data['receipt_email']){
                $captureData['receipt_email'] = $data['receipt_email'];
            }
            $charge = $this->stripe->charges->retrieve($id);
            $charge->capture($captureData);
        }
        catch (ApiErrorException $e) {
            return $e->getMessage();
        }

    }
}
