<?php


namespace App\Providers;

use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $url = env('URL');

        $token = config('telegram.bot_token', 'token');

        Telegram::setWebhook(['url' => route('bot.webhook')]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
