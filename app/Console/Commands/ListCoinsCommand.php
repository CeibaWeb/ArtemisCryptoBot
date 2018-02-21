<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;

class ListCoinsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the current coin list.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        $list = Coin::activeTickersString();

        $message = "Here's my current watchlist: " . $list;

        $this->replyWithMessage(['text' => $message]);
    }
}