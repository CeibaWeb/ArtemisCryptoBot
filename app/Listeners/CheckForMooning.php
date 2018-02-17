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
    public $telegram;
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
        Log::info("Has {$event->snapshot->coin->ticker} mooned recently? " . $event->snapshot->coin->recently_mooned);
        if ($event->snapshot->percent_change_usd > 5.00 && $event->snapshot->coin->recently_mooned === false) {
            $this->sendMoonAlert($event);
        }
    }

    public function sendMoonAlert($event)
    {
        $message = "MOON ALERT! {$event->snapshot->coin->ticker} up {$event->snapshot->percent_change_usd}% on USD over last 24 hours! Current price: \${$event->snapshot->usd_price}";

        $params = [
            'chat_id' => '111658665',
            'text' => $message
        ];

        // artemis 111658665
        // ceiba -253512852

        $this->telegram->sendMessage($params);

        Log::info($message);

        $coin = $event->snapshot->coin->fresh();

        $coin->recently_mooned = true;

        $coin->save();

        ResetCoin::dispatch($coin)->delay(now()->addHours(4));
    }
}
