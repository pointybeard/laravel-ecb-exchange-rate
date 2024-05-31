<?php

declare(strict_types=1);

namespace pointybeard\EcbExchangeRate\Commands;

use Exception;
use Illuminate\Console\Command;
use pointybeard\EcbExchangeRate\Services\EcbScraperService;
use Webmozart\Assert\Assert;

class FetchExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecb-exchange-rate:fetch {currency=USD} {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the EUR to {currency} exchange rate from the ECB website';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected EcbScraperService $ecb_scraper)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currency = $this->argument('currency');

        Assert::string($currency, 'Currency must be a string');

        $force_refresh = $this->option('refresh');

        Assert::boolean($force_refresh, 'Refresh must be a boolean');

        try {
            $rate = $this->ecb_scraper->getExchangeRate($currency, $force_refresh);
            $this->info('The current EUR to '.strtoupper($currency).' exchange rate is: '.$rate);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}