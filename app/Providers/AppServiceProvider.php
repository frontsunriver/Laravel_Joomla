<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (\App::environment('production_ssl')) {
            $url->forceScheme('https');
        }
        Schema::defaultStringLength(191);
	    $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'App');
    }
}
