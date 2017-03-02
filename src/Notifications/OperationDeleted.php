<?php

namespace Seat\Kassie\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

use Seat\Kassie\Calendar\Helpers\Helper;

class OperationDeleted extends Notification
{
	use Queueable;

	public function via($notifiable)
	{
		return ['slack'];
	}

	public function toSlack($notifiable)
	{
		$attachment = Helper::BuildSlackNotificationAttachment($notifiable);

		return (new SlackMessage)
			->error()
			->from('SeAT Calendar', ':calendar:')
			->content(trans('calendar::seat.notification_delete_operation'))
			->attachment($attachment);
	}
}
