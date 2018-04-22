<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;

class RankCoinsReverseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'losers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays currently tracked coins by % losses over last 24 hours';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        $coins = Coin::rankLosers();

        $message = $coins->map(function ($coin, $index) {   
            $rank = $index + 1;

            $sat_price = $coin['btc_price'] * PriceSnapshot::$SATOSHI;

            $text = $index === 0 ? "LOSERS vs BTC last 24 hours:" . PHP_EOL . PHP_EOL : '';

            $text = $text . "{$rank} \t {$coin['ticker']}. \t {$coin['percent_change_btc']}%. \t し{$sat_price}, \t \${$coin['usd_price']}" . PHP_EOL;
            
            return $text;
        });

        $this->replyWithMessage(['text' => $message->implode('')]);
    }
}
