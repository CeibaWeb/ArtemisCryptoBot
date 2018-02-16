<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\CoinApiSdk\Client;

class BotController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function show()
    {
        dd($this->client->getBtcPrices('ETH'));


        $chat = '111658665';

        // Telegram::sendMessage([
        //     'chat_id' => $chat,
        //     'text' => 'My first message! To the moon!'
        // ]);

        dd('wow');
    }
}
