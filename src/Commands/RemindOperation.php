<?php

namespace Seat\Kassie\Calendar\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

use Carbon\Carbon;

use Seat\Kassie\Calendar\Helpers\Settings;
use Seat\Kassie\Calendar\Models\Operation;
use Seat\Kassie\Calendar\Notifications\OperationPinged;

class RemindOperation extends Command
{
	protected $signature = 'calendar:remind';
	protected $description = 'Check for operation to be reminded on Slack';

	private $marks = [15, 30, 60];

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		if (Settings::get('slack_integration') == 1) 
		{
			$ops = Operation::all()->take(-50);
			$now = Carbon::now('UTC');

			foreach($ops as $op) 
			{
				if ($op->status == 'incoming') {
					if(in_array($now->diffInMinutes($op->start_at, false), $this->marks))
					{
						Notification::send($op, new OperationPinged());
					}
				}
			}
		}
	}
}