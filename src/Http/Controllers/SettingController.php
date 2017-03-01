<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Helpers\Settings;

class SettingController extends Controller
{
	public function index() {
		return Settings::get('slack_integration');
	}
}