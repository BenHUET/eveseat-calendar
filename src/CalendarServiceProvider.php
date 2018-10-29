<?php

namespace Seat\Kassie\Calendar;

use Illuminate\Console\Scheduling\Schedule;
use Seat\Kassie\Calendar\Observers\OperationObserver;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Commands\RemindOperation;
use Seat\Services\AbstractSeatPlugin;

/**
 * Class CalendarServiceProvider.
 * @package Seat\Kassie\Calendar
 */
class CalendarServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->addCommands();
        $this->addRoutes();
        $this->addViews();
        $this->addTranslations();
        $this->addMigrations();
        $this->addPublications();
        $this->addObservers();
        $this->configureApi();

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('calendar:remind')->everyMinute();
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
        $this->mergeConfigFrom(__DIR__ . '/Config/calendar.character.menu.php', 'package.character.menu');
        $this->mergeConfigFrom(__DIR__ . '/Config/calendar.corporation.menu.php', 'package.corporation.menu');
        $this->mergeConfigFrom(__DIR__ . '/Config/calendar.permissions.php', 'web.permissions');
        $this->mergeConfigFrom(__DIR__ . '/Config/character.permission.php', 'web.permissions.character');
        $this->mergeConfigFrom(__DIR__ . '/Config/corporation.permission.php', 'web.permissions.corporation');
        $this->mergeConfigFrom(__DIR__ . '/Config/calendar.config.php', 'calendar.config');
    }

    private function addRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    private function addViews()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'calendar');
    }

    private function addTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'calendar');
    }

    private function addMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    private function addPublications() 
    {
        $this->publishes([
            __DIR__ . '/resources/assets/css' => public_path('web/css'),
            __DIR__ . '/resources/assets/vendors/css' => public_path('web/css'),
            __DIR__ . '/resources/assets/js' => public_path('web/js'),
            __DIR__ . '/resources/assets/vendors/js' => public_path('web/js'),
            app_path() . '/../vendor/itsjavi/bootstrap-colorpicker/dist/js' => public_path('web/js'),
            app_path() . '/../vendor/itsjavi/bootstrap-colorpicker/dist/css' => public_path('web/css'),
        ]);
    }

    private function addObservers() 
    {
        Operation::observe(OperationObserver::class);
    }

    private function addCommands() 
    {
        $this->commands([
            RemindOperation::class,
        ]);
    }

    private function configureApi()
    {
        // ensure current annotations setting is an array of path or transform into it
        $current_annotations = config('l5-swagger.paths.annotations');
        if (! is_array($current_annotations))
            $current_annotations = [$current_annotations];
        // merge paths together and update config
        config([
            'l5-swagger.paths.annotations' => array_unique(array_merge($current_annotations, [
                __DIR__ . '/Models',
                __DIR__ . '/Http/Controllers/Api/v1',
            ])),
        ]);
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @example SeAT Web
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Calendar';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/BenHUET/eveseat-calendar';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @example web
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'calendar';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @example eveseat
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'kassie';
    }

    /**
     * Return the plugin installed version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return config('calendar.config.version');
    }
}
