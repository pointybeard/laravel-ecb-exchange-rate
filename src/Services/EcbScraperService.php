<?php

declare(strict_types=1);

namespace pointybeard\EcbExchangeRate\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Webmozart\Assert\Assert;

class EcbScraperService
{
    protected HttpBrowser $browser;

    protected string $url;

    protected int $cache_time;

    protected string $xpath;

    public function __construct(?HttpBrowser $browser = null)
    {
        $this->browser = $browser ?? new HttpBrowser(HttpClient::create());

        Assert::string(config('ecb-exchange-rate.ecb_url'), 'ecb_url must be a string');

        $this->url = config('ecb-exchange-rate.ecb_url');

        Assert::integer(config('ecb-exchange-rate.cache_time'), 'cache_time must be an integer');

        $this->cache_time = config('ecb-exchange-rate.cache_time');

        Assert::string(config('ecb-exchange-rate.xpath'), 'xpath must be a string');

        $this->xpath = config('ecb-exchange-rate.xpath');
    }

    protected function constructXPath(string $currency): string
    {
        return sprintf($this->xpath, $currency);
    }

    public function getExchangeRate(string $currency, bool $force_refresh = false): float
    {
        $cache_key = 'eur_to_'.strtolower($currency).'_rate';
        $xpath = $this->constructXPath($currency);

        if ($force_refresh) {
            return $this->fetchAndCacheRate($cache_key, $xpath);
        }

        return Cache::remember($cache_key, $this->cache_time, function () use ($xpath, $cache_key) {
            return $this->fetchAndCacheRate($cache_key, $xpath);
        });
    }

    protected function fetchAndCacheRate(string $cache_key, string $xpath): float
    {
        try {
            $crawler = $this->browser->request('GET', $this->url);
            $rate = $crawler->filterXPath($xpath)
                ->text();

            Assert::numeric($rate, 'Rate must be a number');

            $rate = (float) $rate;

            Cache::put($cache_key, $rate, $this->cache_time);

            return $rate;
        } catch (Exception $e) {
            Log::error('Failed to fetch exchange rate: '.$e->getMessage());
            if (Cache::has($cache_key)) {

                $rate = Cache::get($cache_key);

                Assert::numeric($rate, 'Rate must be a number');

                return (float) $rate;
            }
            throw new Exception('Failed to fetch new exchange rate and no cached rate is available.');
        }
    }
}
