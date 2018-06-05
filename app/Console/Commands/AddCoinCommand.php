<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;
use Illuminate\Support\Facades\Validator;

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
        $ticker = ['ticker' => $arguments];

        $validator = Validator::make($ticker, [
            'ticker' => 'alpha|between:2,5'
        ]);

        if ($validator->fails()) {
            $this->replyWithMessage(['text' => 'Please enter a coin that actually exists.']);

            return;
        }

        $coin = Coin::firstOrNew($ticker);

        if ($coin->exists) {
            $this->replyWithMessage(['text' => "$coin->ticker rejoins the list!"]);

            $coin->active = true;

            $coin->save();
        }

        if ($coin->exists === false) {
            $this->replyWithMessage(['text' => "A new coin approaches! $coin->ticker added to the list."]);

            $coin->save();
        }

    }
}
