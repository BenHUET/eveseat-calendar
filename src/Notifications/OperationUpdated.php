<?php

namespace Seat\Kassie\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Kassie\Calendar\Helpers\Helper;


class OperationUpdated extends Notification
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
            ->warning()
            ->from('SeAT Calendar', ':calendar:')
            ->content(trans('calendar::seat.notification_edit_operation'))
            ->attachment($attachment);
    }
}
