<?php

namespace Seat\Kassie\Calendar;

use Illuminate\Support\ServiceProvider;

class CalendarServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->addRoutes();
		$this->addViews();
		$this->addTranslations();
		$this->addMigrations();
		$this->addPublications();
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
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
			__DIR__ . '/resources/assets/js' => public_path('web/js')
		]);
    }

}
