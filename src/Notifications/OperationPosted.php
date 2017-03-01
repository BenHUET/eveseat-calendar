<?php

namespace Seat\Kassie\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

use Seat\Kassie\Calendar\Helpers\Helper;
use Seat\Kassie\Calendar\Helpers\Settings;

class OperationPosted extends Notification
{
	use Queueable;

	public function __construct()
	{
		
	}

	public function via($notifiable)
	{
		return ['slack'];
	}

	public function toSlack($notifiable)
	{
		$url = url('/calendar/operation');

		$fields = array();

		$fields['Date (EVE Time)'] = $notifiable->start_at->format('M j @ H:i');
		if ($notifiable->getDurationAttribute())
			$fields['Duration'] = $notifiable->getDurationAttribute();

		$fields['Type'] = $notifiable->type;
		$fields['Importance'] = Helper::ImportanceAsEmoji($notifiable->importance, ':full_moon:', ':last_quarter_moon:', ':new_moon:');

		if ($notifiable->fc)
			$fields['Fleet commander'] = $notifiable->fc;

		if ($notifiable->staging)
			$fields['Staging'] = $notifiable->staging;

		return (new SlackMessage)
			->success()
			->from('SeAT', ':calendar:')
			->content(trans('calendar::seat.notification_new_operation'))
			->attachment(
				function ($attachment) use ($url, $notifiable, $fields) {
					$attachment->title($notifiable->title, $url)
					 	->fields($fields);
				}
			);
	}
}
