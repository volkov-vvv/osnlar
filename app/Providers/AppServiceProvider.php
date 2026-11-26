<?php

namespace App\Providers;

use App\Helpers\Telegram;
use App\Models\Setting;
use App\Service\PaymentService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentService();
        });

        $this->app->bind(LidService::class, function ($app) {
            return new LidService();
        });

        $this->app->bind(Telegram::class, function ($app) {
            return new Telegram(new Http(), config('bots.bot'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer(['layouts.main2', 'layouts.main-lid'], function ($view) {
            $view->with('newYearThemeEnabled', Setting::isNewYearThemeEnabled());
        });
    }
}
