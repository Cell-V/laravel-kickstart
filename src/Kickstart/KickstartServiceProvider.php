<?php

namespace Samrap\Kickstart;

use Illuminate\Support\ServiceProvider;

class KickstartServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish app files.
        $this->publishes([
            __DIR__.'/../../publish/app/' => app_path(),
        ], 'app');

        // Publish view and asset files.
        $this->publishes([
            __DIR__.'/../../publish/resources/' => resource_path(),
        ], 'resources');

        // Publish NPM files. Note that for these to apply, the --force flag
        // must be specified.
        $this->publishes([
            __DIR__.'/../../publish/gulpfile.js' => base_path('gulpfile.js'),
            __DIR__.'/../../publish/package.json' => base_path('package.json'),
        ], 'npm');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // First, we will register the Kickstart command here so that we have
        // everything we need in one swing. That's what kickstart is for.
        $this->commands(['Samrap\Kickstart\Commands\Kickstart']);

        // Next, we need to load some third party providers and aliases. Rather
        // than adding all of those manually in the configuration file, we
        // can do that here and keep those config files a bit cleaner.
        $this->registerPackages();
    }

    /**
     * Register the providers and aliases from additional packages.
     *
     * @return void
     */
    private function registerPackages()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        if (config('app.debug')) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
            $this->app->alias('Debugbar', 'Barryvdh\Debugbar\Facade');
        }

        $this->app->alias('Form', 'Collective\Html\FormFacade');
        $this->app->alias('Html', 'Collective\Html\HtmlFacade');
    }
}
