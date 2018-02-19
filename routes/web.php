<?php


use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'BotController@show');


$token = config('telegram.bot_token', 'token');


Route::post("$token/webhook", function () {
    $update = Telegram::commandsHandler(true);

    Log::info($update->getMessage()->getChat()->getId());

    return 'ok';
});

