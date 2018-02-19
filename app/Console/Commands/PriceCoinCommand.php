<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\CoinApiSdk\Client;
use App\Coin;
use Illuminate\Support\Facades\Validator;

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

        $ticker = ['ticker' => $arguments];

        $validator = Validator::make($ticker, [
            'ticker' => 'alpha|between:2,4'
        ]);

        if ($validator->fails()) {
            $this->replyWithMessage(['text' => 'Please enter a coin that actually exists.']);

            return;
        }

        $coin = Coin::where('ticker', '=', $ticker)->withLastPriceSnapshot()->get()->first();
        
        if ($coin->exists) {
            $satoshi_price = $coin->lastPriceSnapshot->btc_price * 100000000;

            // Log::info($coin->ticker);

            // Log::info('btc price');
            // Log::info($coin->lastPriceSnapshot->btc_price);

            // Log::info('sat price');
            // Log::info($satoshi_price);

            if ($coin->ticker === 'BTC') {
                $satoshi_price = 100000000;
            }

            $message = "Current price of {$coin->ticker}: \${$coin->lastPriceSnapshot->usd_price}. {$satoshi_price} sats.";

        } else {
            $message = "You dun fucked up. Please enter a proper ticker to get a price";
        }

        $this->replyWithMessage(['text' => $message]);
    }

}
