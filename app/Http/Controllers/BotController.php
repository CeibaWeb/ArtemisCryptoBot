<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\CoinApiSdk\Client;

class BotController extends Controller
{
    public function show()
    {
        echo 'Welcome to Artemis Crypto AI';
    }
}
