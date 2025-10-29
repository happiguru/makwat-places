<?php

namespace Mkwat\Places\Laravel;

use Illuminate\Support\ServiceProvider;
use Mkwat\Places\CameroonPlaces;

class PlacesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'makwat_places');

        $this->app->singleton(CameroonPlaces::class, function () {
            return CameroonPlaces::makeDefault();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('makwat_places.php'),
        ], 'config');
    }
}
