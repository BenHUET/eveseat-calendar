<?php

namespace Seat\Kassie\Calendar;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

use Seat\Kassie\Calendar\Observers\OperationObserver;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Commands\RemindOperation;
use Seat\Kassie\Http\Middlewares;

class CalendarServiceProvider extends ServiceProvider
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

		$this->app->booted(function () {
			$schedule = $this->app->make(Schedule::class);
			$schedule->command('calendar:remind')->everyMinute();
		});
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
		$this->mergeConfigFrom(__DIR__ . '/Config/calendar.permissions.php', 'web.permissions');
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
			__DIR__ . '/resources/assets/vendors/js' => public_path('web/js')
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

}
