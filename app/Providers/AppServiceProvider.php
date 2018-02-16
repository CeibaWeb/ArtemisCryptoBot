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

        $token = env('TELEGRAM_BOT_TOKEN');

        Telegram::setWebhook(['url' => "$url/$token/webhook"]);
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
