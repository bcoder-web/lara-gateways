<?php

namespace Betacoders\Gateway\facades;

use Betacoders\Gateway\interfaces\GatewayServiceInterface;
use Illuminate\Support\Facades\Facade;

class GatewayFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GatewayServiceInterface::class;
    }
}

