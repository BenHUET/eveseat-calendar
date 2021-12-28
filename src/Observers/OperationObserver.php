<?php

namespace Seat\Kassie\Calendar\Observers;

use Illuminate\Support\Facades\Notification;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Notifications\OperationPosted;
use Seat\Kassie\Calendar\Notifications\OperationUpdated;
use Seat\Kassie\Calendar\Notifications\OperationCancelled;
use Seat\Kassie\Calendar\Notifications\OperationActivated;
use Seat\Kassie\Calendar\Notifications\OperationEnded;

/**
 * Class OperationObserver.
 *
 * @package Seat\Kassie\Calendar\Observers
 */
class OperationObserver
{
    /**
     * @param \Seat\Kassie\Calendar\Models\Operation $operation
     */
    public function created(Operation $operation)
    {
        if (setting('kassie.calendar.slack_integration', true) == 1 && !is_null($operation->integration))
            Notification::send($operation, new OperationPosted());
    }

    /**
     * @param \Seat\Kassie\Calendar\Models\Operation $new_operation
     */
    public function updating(Operation $new_operation)
    {
        if (setting('kassie.calendar.slack_integration', true) == 1 && !is_null($new_operation->integration)) {
            $old_operation = Operation::find($new_operation->id);
            if ($old_operation->is_cancelled != $new_operation->is_cancelled) {
                if ($new_operation->is_cancelled == true)
                    Notification::send($new_operation, new OperationCancelled());
                else
                    Notification::send($new_operation, new OperationActivated());
            }
            elseif ($old_operation->end_at != $new_operation->end_at && $new_operation->is_cancelled == false) {
                Notification::send($new_operation, new OperationEnded());
            }
            else {
                Notification::send($new_operation, new OperationUpdated());
            }
        }
    }
}
