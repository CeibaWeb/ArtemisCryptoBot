<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\Coin;
use App\PriceSnapshot;
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
            'ticker' => 'alpha|between:2,5'
        ]);

        if ($validator->fails()) {
            $this->replyWithMessage(['text' => 'Please enter a coin that actually exists.']);

            return;
        }
        
        $coin = Coin::where('coins.ticker', '=', $arguments)->withLastSnapshot()->get()->first();
        
        if (!($coin instanceof Coin)) {
            $this->replyWithMessage(['text' => "Coin not tracked. Add to list to begin tracking."]);

            return;
        }

        if ($coin->exists) {
            $satoshi_price = $coin->btc_price * PriceSnapshot::$SATOSHI;

            $message = "{$coin->ticker}: \${$coin->usd_price} | し{$satoshi_price} | {$coin->percent_change_btc}% Δし / 24";
        }

        $this->replyWithMessage(['text' => $message]);
    }
}
