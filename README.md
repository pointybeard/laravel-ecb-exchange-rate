# Laravel ECB (European Central Bank) Exchange Rate

This is a Laravel package that scrapes exchange rates from the European Central Bank (ECB) website. It provides an easy-to-use service, facade, and artisan command.

## Installation

You can install the package via Composer:

```bash
composer require pointybeard/laravel-ecb-exchange-rate
```

## Usage

```php
use pointybeard\EcbExchangeRate\Services\EcbScraperService;

echo (new EcbScraperService)->getExchangeRate('USD');
```

or via the `EcbExchangeRate` facade like so:

```php

use pointybeard\EcbExchangeRate\Facades\EcbExchangeRate;

echo EcbExchangeRate::getExchangeRate('USD');
```

There is also an artisan command

```bash
php artisan ecb-exchange-rate:fetch USD
```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/laravel-ecb-exchange-rate/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/laravel-ecb-exchange-rate/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"Laravel ECB (European Central Bank) Exchange Rate" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
