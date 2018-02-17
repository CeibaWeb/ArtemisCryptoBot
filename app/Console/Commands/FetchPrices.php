<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\CoinApiSdk\Client;
use App\Coin;
use App\PriceSnapshot;

class FetchPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches prices and triggers relevant events';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tickers = Coin::activeTickers();

        Log::info("Fetching prices for: " . $tickers->implode(' '));

        $res = $this->client->getUsdPrices($tickers->toArray());

        $prices = collect($res)->map(function($coin) {
            $coin = $coin['USD'];

            $args = [
                'ticker' => $coin['FROMSYMBOL'],
                'usd_price' => $coin['PRICE'],
                'percent_change_usd' => $coin['CHANGEPCT24HOUR'],
                'market_cap_usd' => $coin['MKTCAP']
            ];

            $snapshot = PriceSnapshot::create($args);

            Log::info("{$snapshot->coin->ticker} price change: {$snapshot->percent_change_usd}%");
        });
    }
}
