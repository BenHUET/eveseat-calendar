<?php

namespace Seat\Kassie\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

use Seat\Kassie\Calendar\Helpers\Helper;

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
		$attachment = Helper::BuildSlackNotificationAttachment($notifiable);

		return (new SlackMessage)
			->success()
			->from('SeAT Calendar', ':calendar:')
			->attachment($attachment);
	}
}
