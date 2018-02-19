<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;

class PriceCoinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display coin price';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        Log::info($arguments);

        Log::info(Coin::find($arguments[0]));

        switch (count($arguments)) {
            case 1:
                $coin = Coin::where('ticker', '=', strtoupper($arguments[0]))->withLastPriceSnapshot()->get()->first();

                Log::info($coin->toArray());

                $satoshi_price = $coin->lastPriceSnapshot->btc_price * 100000000;

                $message = "Current price of {$coin->ticker}: \${$coin->lastPriceSnapshot->usd_price}. {$satoshi_price} sats.";

                break;
            default:
                $message = "You dun fucked up. Please enter a proper ticker to get a price";

                break;
        }


        $this->replyWithMessage(['text' => $message->implode('')]);
    }
}
