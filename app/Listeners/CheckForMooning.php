<?php

namespace App\Listeners;

use App\Events\SavePriceSnapshot;
use Telegram\Bot\Api;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\ResetCoin;
use Illuminate\Support\Facades\Log;

class CheckForMooning
{
    public $commandBus;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Api $api)
    {
        $this->telegram = $api;
    }

    /**
     * Handle the event.
     *
     * @param  SavePriceSnapshot  $event
     * @return void
     */
    public function handle(SavePriceSnapshot $event)
    {
        if ($event->snapshot->percent_change_usd > 10.00) {
            $this->sendMoonAlert($event);
        }
    }

    public function sendMoonAlert($event)
    {
        $message = "MOON ALERT! {$event->snapshot->coin->ticker} up {$event->snapshot->percent_change_usd}% over last 24 hours! Current price: \${$event->snapshot->usd_price}";

        $params = [
            'chat_id' => '111658665',
            'text' => $message
        ];

        $this->telegram->sendMessage($params);

        Log::info($message);

        $coin = $event->snapshot->coin->fresh();

        $coin->recently_mooned = true;

        $coin->save();

        ResetCoin::dispatch($coin)->delay(now()->addHours(4));
    }
}
