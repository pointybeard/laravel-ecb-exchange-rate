<?php

declare(strict_types=1);

return [
    'ecb_url' => 'https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html',
    'cache_time' => 86400,
    'xpath' => '//td[@id="%s"]/following-sibling::td[@class="spot number"]//span[@class="rate"]',
];
