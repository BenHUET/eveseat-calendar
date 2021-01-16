<?php

namespace Seat\Kassie\Calendar\Observers;

use Illuminate\Support\Facades\Notification;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Notifications\OperationPosted;
use Seat\Kassie\Calendar\Notifications\OperationUpdated;
use Seat\Kassie\Calendar\Notifications\OperationCancelled;
use Seat\Kassie\Calendar\Notifications\OperationActivated;


class OperationObserver
{
    public function created(Operation $operation)
    {
        if (setting('kassie.calendar.slack_integration', true) == 1 && !is_null($operation->integration)) {
            if (setting('kassie.calendar.event.create', true) == 1) {
                Notification::send($operation, new OperationPosted());
            }
        }
    }

    public function updating(Operation $new_operation)
    {
        if (setting('kassie.calendar.slack_integration', true) == 1 && !is_null($new_operation->integration)) {
            $old_operation = Operation::find($new_operation->id);
            if ($old_operation->is_cancelled != $new_operation->is_cancelled) {
                if ($new_operation->is_cancelled == true) {
                    if (setting('kassie.calendar.event.cancel', true) == 1) {
                        Notification::send($new_operation, new OperationCancelled());
                    }
                }
                else {
                    if (setting('kassie.calendar.event.create', true) == 1) {
                        Notification::send($new_operation, new OperationActivated());
                    }
                }
            }
            else {
                if (setting('kassie.calendar.event.edit', true) == 1) {                
                    Notification::send($new_operation, new OperationUpdated());
                }
            }
        }
    }
}
