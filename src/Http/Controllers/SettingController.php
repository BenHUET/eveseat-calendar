<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;

use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Helpers\Settings;
use Seat\Kassie\Calendar\Models\Setting;

class SettingController extends Controller
{
	public function index() {
		$settings = Setting::all()->first();

		return view('calendar::setting.index', [
			'settings' => $settings
		]);
	}

	public function updateSlack(Request $request) 
	{		
		if (auth()->user()->has('calendar.setup')) {
			$settings = Setting::all()->first();

			$settings->slack_integration = $request->slack_integration == 1 ? 1 : 0;
			$settings->slack_webhook = $request->slack_webhook;
			$settings->slack_emoji_importance_full = $request->slack_emoji_importance_full;
			$settings->slack_emoji_importance_half = $request->slack_emoji_importance_half;
			$settings->slack_emoji_importance_empty = $request->slack_emoji_importance_empty;

			$settings->save();

			return redirect()->back();
		}

		return redirect()->route('auth.unauthorized');
	}
}