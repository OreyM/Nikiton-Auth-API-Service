<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(storage_path('secrets/auth'));

        Passport::tokensExpireIn(CarbonInterval::hours(24));
        Passport::refreshTokensExpireIn(CarbonInterval::hours(48));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
    }
}
