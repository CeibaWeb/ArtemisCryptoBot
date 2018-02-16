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
        $coin = Coin::find(['ticker' => $arguments]);

        if ($coin) {
            $this->replyWithMessage(['text' => "Coin $coin->ticker already on the list. Use the remove command if you want to remove it."]);
            return;
        }

        $coin = Coin::create(['ticker' => $arguments]);

        $this->replyWithMessage(['text' => "Success! $coin->ticker added."]);
    }
}
