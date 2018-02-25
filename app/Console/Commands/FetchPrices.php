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
        $tickers = Coin::getTickers();

       //Log::info("Fetching prices for: " . Coin::activeTickersString() );

        $response = $this->client->getPrices($tickers->toArray());

        $prices = collect($res)->map(function ($coin) {

            $usd = $coin['USD'];
            $btc = $coin['BTC'];

            // Log::info("{$usd['FROMSYMBOL']} USD percent change: {$usd['CHANGEPCT24HOUR']}");
            // Log::info("{$btc['FROMSYMBOL']} BTC percent change: {$btc['CHANGEPCT24HOUR']}");

            $btc_change = $btc['CHANGEPCT24HOUR'];

            if ($btc['FROMSYMBOL'] === 'BTC') {
                $btc_change = 0;
            }

            $btc_price = $btc['PRICE'];

            if ($btc['FROMSYMBOL'] === 'BTC') {
                $btc_price = PriceSnapshot::$SATOSHI;
            }

            $args = [
                'ticker' => $usd['FROMSYMBOL'],
                'usd_price' => $usd['PRICE'],
                'btc_price' => $btc_price,
                'percent_change_usd' => $usd['CHANGEPCT24HOUR'],
                'percent_change_btc' => $btc_change,
                'market_cap_usd' => $usd['MKTCAP'],
                'market_cap_btc' => $btc['MKTCAP']
            ];

            $snapshot = PriceSnapshot::create($args);

            Log::info("{$snapshot->coin->ticker} price change: {$snapshot->percent_change_usd}");
        });
        
    }
}
