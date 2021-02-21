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
            }
            
            $facade = ViteManifest::class;

            return sprintf("<?php echo $facade::embed(e('%s')); ?>", $entry);
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
