<?php

namespace OhSeeSoftware\LaravelViteManifest;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use OhSeeSoftware\LaravelViteManifest\Facades\ViteManifest;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Blade::directive('vite', function ($entry) {
            if (empty($entry)) {
                $entry = 'js/app.js';
            } else {
                // Strip string quotes
                $entry = str_replace("'", '', $entry);
            }

            return ViteManifest::embed($entry);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('laravel-vite-manifest', function () {
            return new LaravelViteManifest;
        });
    }
}
