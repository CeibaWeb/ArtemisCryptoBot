<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;

class RankCoinsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'winners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays currently tracked coins by % gain over last 24 hours';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        $coins = Coin::byDailyPercentGain();

        $message = $coins->slice(0, 10)->map(function ($coin, $index) {
            $text = $index === 0 ? "WINNERS vs BTC last 24 hours:" . PHP_EOL . PHP_EOL : '';
            
            $rank = $index + 1;

            $text = $text . "{$rank} \t {$coin->ticker}. \t {$coin->lastPriceSnapshot->percent_change_btc}%. \t し{$coin->lastPriceSnapshot->satoshi_price}, \t \${$coin->lastPriceSnapshot->usd_price}" . PHP_EOL;
            
            return $text;
        });

        $this->replyWithMessage(['text' => $message->implode('')]);
    }
}
