<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DrewM\MailChimp\MailChimp;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Horizon::auth(function ($request) {
            return auth()->check();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MailChimp::class, function($app) {
            return new MailChimp(env('MAILCHIMP_API_KEY'));
        });
    }
}
