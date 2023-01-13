<?php

namespace Betacoders\Gateway\services\stripe;

use Stripe\Exception\ApiErrorException;

class Customer extends Stripe
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
     * @param array $data
     * @return array|mixed|string
     */
    public function create(array $data){
        try {
            $customerData = [];
            if ($data['address']){
                $customerData['address'] = $data['address'];
            }
            if ($data['description']){
                $customerData['description'] = $data['description'];
            }
            if ($data['email']){
                $customerData['email'] = $data['email'];
            }
            if ($data['metadata']){
                $customerData['metadata'] = $data['metadata'];
            }
            if ($data['name']){
                $customerData['name'] = $data['name'];
            }
            if ($data['payment_method']){
                $customerData['payment_method'] = $data['payment_method'];
            }
            if ($data['phone']){
                $customerData['phone'] = $data['phone'];
            }
            $customer = $this->stripe->customers->create($customerData);
            return $customer->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

    /**
     * @param string $id
     * @return array|mixed|string
     */
    public function retrive(string $id){
        try {
            $customer = $this->stripe->customers->retrieve($id);
            return $customer->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

    /**
     * @param string $id
     * @return array|mixed|string
     */
    public function delete(string $id){
        try {
            $customer = $this->stripe->customers->delete($id);
            return $customer->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

    /**
     * @param array $params
     * @return array|mixed|string
     */
    public function all(array $params=['limit'=>10]){
        try {
            $customer = $this->stripe->customers->all($params);
            return $customer->jsonSerialize();
        }catch (ApiErrorException $exception){
            return $exception->getMessage();
        }
    }

}
