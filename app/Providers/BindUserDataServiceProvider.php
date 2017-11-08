<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BindUserDataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
		view()->composer(
            'layouts.partial.header',
            'App\Http\ViewComposer\HeaderComposer'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
