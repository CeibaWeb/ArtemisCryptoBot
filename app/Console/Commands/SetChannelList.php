<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use App\Coin;
use App\PriceSnapshot;

class SetChannelListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'winners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the list channel';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        Log::info($this->update);

        $this->replyWithMessage(['text' => $message->implode('Thanks')]);
    }
}
