<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Coin;

class CoinsSort extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coins:sort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coins = Coin::byDailyPercentGain();

        $message = $coins->map(function ($coin, $index) {
            $text = $index === 0 ? "Top 10 coins ranked by % gain over 24 hours:" . PHP_EOL : '';
            
            $rank = $index + 1;

            $text = $text . "{$rank}. {$coin->ticker}. {$coin->lastPriceSnapshot->percent_change_usd}% change. Current price \${$coin->lastPriceSnapshot->usd_price}" . PHP_EOL;
            
            return $text;
        });

        dd($message->slice(0, 10)->implode(''));
    }
}
