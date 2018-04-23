<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use App\Coin;
use App\PriceSnapshot;

class SetChannelListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'setList';

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
        Log::info('from');
        Log::info($from = $this->update->getMessage()->getFrom());

        Log::info('chat');
        Log::info($chat = $this->update->getMessage()->getChat());

        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        Log::info('response');
        Log::info($telegram->get('getChatMember', [
            'chat_id' => $chat->getId(),
            'user_id' => $from->getId()
        ]));

        $this->replyWithMessage(['text' => 'thanks']);
    }
}
