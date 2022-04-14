<?php

namespace maree\aramex;

use Illuminate\Support\ServiceProvider;

class AramexServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/aramex.php' => config_path('aramex.php'),
        ],'aramex');

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/aramex.php', 'aramex'
        );

    }
}
