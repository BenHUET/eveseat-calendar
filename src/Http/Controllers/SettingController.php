<?php

namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Kassie\Calendar\Models\Tag;
use Seat\Notifications\Models\Integration;

/**
 * Class SettingController.
 *
 * @package Seat\Kassie\Calendar\Http\Controllers
 */
class SettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $tags = Tag::all();
        $notification_channels = Integration::where('type', 'slack')->get();

        return view('calendar::setting.index', [
            'tags' => $tags,
            'slack_integrations' => $notification_channels,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSlack(Request $request)
    {
        $validated_data = $request->validate([
            'slack_integration_default_channel' => ['nullable', 'exists:integrations,id'],
            'slack_emoji_importance_full' => ['nullable', 'string'],
            'slack_emoji_importance_half' => ['nullable', 'string'],
            'slack_emoji_importance_empty' => ['nullable', 'string'],
        ]);

        setting([
            'kassie.calendar.slack_integration',
            $request->slack_integration == 1 ? 1 : 0,
        ], true);

        setting([
            'kassie.calendar.slack_integration_default_channel',
            $validated_data['slack_integration_default_channel'],
        ], true);

        setting([
            'kassie.calendar.slack_emoji_importance_full',
            $validated_data['slack_emoji_importance_full'],
        ], true);

        setting([
            'kassie.calendar.slack_emoji_importance_half',
            $validated_data['slack_emoji_importance_half'],
        ], true);

        setting([
            'kassie.calendar.slack_emoji_importance_empty',
            $validated_data['slack_emoji_importance_empty'],
        ], true);

        return redirect()->back();
    }
}
