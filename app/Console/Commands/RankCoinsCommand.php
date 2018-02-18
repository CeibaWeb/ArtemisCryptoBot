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
    protected $name = 'rank';

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
        // Sort coins by biggest % gain

        // 

        $coins = Coin::byDailyPercentGain();

        // Arrange coins into a list

        $message = $coins->slice(0, 9)->map(function ($coin, $index) {
            $text = $index === 0 ? "Top 10 coins ranked by % gain over 24 hours:" . PHP_EOL : '';
            
            $rank = $index + 1;

            $text = $text . "{$rank}. {$coin->ticker}. {$coin->lastPriceSnapshot->percent_change_usd}% change. Current price \${$coin->lastPriceSnapshot->usd_price}" . PHP_EOL;
            
            return $text;
        });

        $this->replyWithMessage(['text' => $message->implode('')]);
    }
}
