<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;

class RemoveCoinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes a coin from the moon watch list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        //Log::info($arguments);

        if (empty($arguments)) {
            //$this->replyWithMessage(['text' => 'Please enter a coin.']);
            Log::info('no text entered');
            return;
        }

        $coin = Coin::find([$arguments])->first();

        Log::info($coin);

        if ($coin) {
            $coin->active = false;
            $coin->save();
        }

        $message = $coin ? "Purged! $coin->ticker has been mercilessly removed from the list." : "No coin on the list by that ticker. Did you mean to add?";

        //Log::info($coin);

        Log::info($message);

        //$this->replyWithMessage(['text' => $message]);
    }
}
