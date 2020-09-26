<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vtpass
 * Class Name: LaravelVTPassServiceProvider.php
 * Date Created: 7/13/20
 * Time Created: 6:40 PM
 */

namespace HenryEjemuta\LaravelVTPass;

use HenryEjemuta\LaravelVTPass\Console\InstallLaravelVTPass;
use Illuminate\Support\ServiceProvider;


class LaravelVTPassServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Boot the service provider.
     */
    public function boot()
    {
        if (function_exists('config_path') && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('vtpass.php'),
            ], 'config');

            $this->commands([
                InstallLaravelVTPass::class,
            ]);

        }
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'vtpass');

        $this->app->singleton('vtpass', function ($app) {
            $baseUrl = config('vtpass.base_url');
            $instanceName = 'vtpass';


            return new VTPass(
                $baseUrl,
                $instanceName,
                config('vtpass')
            );
        });

    }
}
