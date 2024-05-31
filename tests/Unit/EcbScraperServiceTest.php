<?php

declare(strict_types=1);

namespace pointybeard\EcbExchangeRate\Tests\Unit;

use Exception;
use Illuminate\Support\Facades\Cache;
use Mockery;
use pointybeard\EcbExchangeRate\Services\EcbScraperService;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class EcbScraperServiceTest extends TestCase
{
    public function test_exchange_rate_fetches_new_rate()
    {
        $currency = 'USD';
        $cache_key = 'eur_to_usd_rate';
        $expected_rate = 1.0815;
        $html = '<table class="forextable"><thead><tr><th colspan="2" class="sort sortableColumn">Currency<span></span></th><th class="number ">Spot</th><th class="centered">Chart</th></tr></thead><tbody><tr><td id="USD" class="currency"><a href="eurofxref-graph-usd.en.html">USD</a></td><td class="alignLeft"><a href="eurofxref-graph-usd.en.html">US dollar</a></td><td class="spot number"><a href="eurofxref-graph-usd.en.html"><span class="rate">1.0815</span></a><span class="trend down"></span></td><td class="w5"><a href="eurofxref-graph-usd.en.html"><img src="/shared/img/fxref.gif" width="19" height="15" alt="USD" title=""></a></td></tr></tbody></table>';

        Cache::shouldReceive('put')
            ->once()
            ->with($cache_key, $expected_rate, Mockery::type('int'))
            ->andReturn($expected_rate);

        $browser = Mockery::mock(HttpBrowser::class);

        $crawler = new Crawler($html);

        $browser->shouldReceive('request')
            ->once()
            ->with('GET', config('ecb-exchange-rate.ecb_url'))
            ->andReturn($crawler);

        $rate = (new EcbScraperService($browser))->getExchangeRate($currency, true);

        $this->assertEquals($expected_rate, $rate);
    }

    public function test_fetch_and_cache_rate_handles_exception()
    {
        $currency = 'USD';
        $cache_key = 'eur_to_usd_rate';

        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('put')
            ->never();

        $browser = Mockery::mock(HttpBrowser::class);

        $browser->shouldReceive('request')
            ->once()
            ->with('GET', config('ecb-exchange-rate.ecb_url'))
            ->andThrow(new Exception('Error fetching rate'));

        $service = new EcbScraperService($browser);

        $this->expectException(Exception::class);

        $this->expectExceptionMessage('Failed to fetch new exchange rate and no cached rate is available.');

        $service->getExchangeRate($currency, true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
