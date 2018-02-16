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
       Telegram::setWebhook(['url' => 'https://fa917b3f.ngrok.io/409010975:AAF_gCgP1CZk8EB-8BYHUXbgpsD_s55cxTE/webhook']);
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
