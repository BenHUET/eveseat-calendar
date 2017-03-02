<?php

namespace Seat\Kassie\Calendar\Observers;

use Illuminate\Support\Facades\Notification;

use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Notifications\OperationPosted;
use Seat\Kassie\Calendar\Notifications\OperationUpdated;
use Seat\Kassie\Calendar\Notifications\OperationDeleted;
use Seat\Kassie\Calendar\Notifications\OperationCancelled;
use Seat\Kassie\Calendar\Notifications\OperationActivated;
use Seat\Kassie\Calendar\Helpers\Settings;

class OperationObserver
{
	public function created(Operation $operation)
	{
		if (Settings::get('slack_integration') == true)
			Notification::send($operation, new OperationPosted());
	}

	public function updating(Operation $new_operation)
	{
		if (Settings::get('slack_integration') == true) {
			$old_operation = Operation::find($new_operation->id);
			if ($old_operation->is_cancelled != $new_operation->is_cancelled) {
				if ($new_operation->is_cancelled == true)
					Notification::send($new_operation, new OperationCancelled());
				else
					Notification::send($new_operation, new OperationActivated());
			}
			else {
				Notification::send($new_operation, new OperationUpdated());
			}
		}
	}

	public function deleted(Operation $operation)
	{
		if (Settings::get('slack_integration') == true)
			Notification::send($operation, new OperationDeleted());
	}
}