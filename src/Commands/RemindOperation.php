<?php

namespace Seat\Kassie\Calendar\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Notifications\OperationPinged;

/**
 * Class RemindOperation.
 *
 * @package Seat\Kassie\Calendar\Commands
 */
class RemindOperation extends Command
{
    /**
     * @var string
     */
    protected $signature = 'calendar:remind';

    /**
     * @var string
     */
    protected $description = 'Check for operation to be reminded on Slack';

    /**
     * Process the command.
     */
    public function handle()
    {
        # Ensure we send reminders starting with furthest in the future. That way
        # when more than one event is being reminded, the last reminder in chat
        # is the next event to occur.
        $configured_marks = setting('kassie.calendar.notify_operation_interval', true);
        if ($configured_marks === null || !setting('kassie.calendar.slack_integration', true)) return;
        $marks = explode(',', $configured_marks);
        rsort($marks);

        foreach ($marks as $mark)
        {
            $when = Carbon::now('UTC')->floorMinute()->addMinutes($mark);
            $ops = Operation::where('is_cancelled', false)
                ->where('start_at', $when)
                ->get();
            foreach($ops as $op)
            {
                Notification::send($op, new OperationPinged());
            }
        }
    }
}
