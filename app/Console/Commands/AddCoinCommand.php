<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;

class AddCoinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a coin to the moon watch list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        //Log::info($arguments);

        if (empty($arguments)) {
            $this->replyWithMessage(['text' => 'Please enter a coin.']);
            return;
        }

        $coin = Coin::firstOrNew(['ticker' => $arguments]);

        if ($coin->exists) {
            $coin->active = true;

            $coin->save();

            $this->replyWithMessage(['text' => "$coin->ticker rejoins the list!"]);
        }

        if ($coin->exists === false) {
            $coin->save();

            $this->replyWithMessage(['text' => "A new coin approaches! $coin->ticker added to the list."]);
        }

        //Log::info($coin);

    }
}
