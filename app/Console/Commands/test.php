<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Coin;
use App\CoinApiSdk\Client;
use Illuminate\Support\Facades\Validator;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coins = Coin::byDailyPercentLoss();


        $message = $coins->slice(0, 10)->map(function ($coin, $index) {
            $text = $index === 0 ? "LOSERS vs BTC last 24 hours:" . PHP_EOL . PHP_EOL : '';
            
            $rank = $index + 1;

            if (! (gettype($coin->lastPriceSnapshot) === "object")) {
                return;
            }

            $text = $text . "{$rank} \t {$coin->ticker}. \t {$coin->lastPriceSnapshot->percent_change_btc}% change. \t し{$coin->lastPriceSnapshot->satoshi_price}, \t \${$coin->lastPriceSnapshot->usd_price}" . PHP_EOL;
            
            return $text;
        });

        dd($messge);
    }
}
