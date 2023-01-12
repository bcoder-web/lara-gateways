<?php

namespace Betacoders\Gateway\interfaces;

use Stripe\Exception\ApiErrorException;

interface GatewayServiceInterface
{
    /**
     * @param $data = additional data form charge api of stripe
     * @throws \Throwable
     * @throws ApiErrorException
     */
    public function charge($data = []): \Stripe\ApiResponse|\Stripe\ErrorObject|array;

    public function setSource(string $source): void;

    public function getSource(): string;

    public function setCustomer(string $customerId): void;

    public function getCustomer(): string;

    public function setAmount($amount): void;

    public function getAmount(): float;

    public function setCurrency(string $currency): void;

    public function getCurrency(): string;
}
