<?php

declare(strict_types=1);

namespace pointybeard\EcbExchangeRate\Facades;

use Illuminate\Support\Facades\Facade;

class EcbExchangeRate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ecb-exchange-rate';
    }
}
