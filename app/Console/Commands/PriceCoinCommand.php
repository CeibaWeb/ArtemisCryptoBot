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

        if (!($coin instanceof Coin)) {
            $this->replyWithMessage(['text' => "Coin not tracked. Add to list to begin tracking."]);
            return;
        }

        if ($coin->exists) {
            // Log::info($coin->ticker);

            // Log::info('btc price');
        // Log::info($coin->lastPriceSnapshot->btc_price);

            // Log::info('sat price');
            // Log::info($satoshi_price);

            $message = "Current price of {$coin->ticker}: \${$coin->lastPriceSnapshot->usd_price}. し{$coin->lastPriceSnapshot->satoshi_price}.";

        } else {
            $message = "You dun fucked up. Please enter a proper ticker to get a price";
        }

        $this->replyWithMessage(['text' => $message]);
    }

}
