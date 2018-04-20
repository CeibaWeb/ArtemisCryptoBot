<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
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

            $snapshot = $coin->lastPriceSnapshot;

            if (! $coin->hasLastPriceSnapshot()) {
                $this->replyWithMessage(['text' => 'Having problems finding a price. Try again soon']);

                return;
            }

            $message = "{$coin->ticker}: \${$snapshot->usd_price} | し{$snapshot->satoshi_price} | {$snapshot->percent_change_btc}% Δし / 24";

        }

        $this->replyWithMessage(['text' => $message]);
    }

}
