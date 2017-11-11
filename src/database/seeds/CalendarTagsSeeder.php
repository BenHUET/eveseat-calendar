<?php

use Illuminate\Database\Seeder;

class CalendarTagsSeeder extends Seeder
{
	public function run()
	{
		\DB::table('calendar_tags')->insert([
			[
				'name' => 'RED PEN',
				'bg_color' => '#ff0000',
				'text_color' => '#ffffff',
				'order' => '500'
			],
			[
				'name' => 'CTA',
				'bg_color' => '#ff4600',
				'text_color' => '#ffffff',
				'order' => '500'
			],
			[
				'name' => 'PVP',
				'bg_color' => '#8d0096',
				'text_color' => '#ffffff',
				'order' => '250'
			],
			[
				'name' => 'PVE',
				'bg_color' => '#009600',
				'text_color' => '#ffffff',
				'order' => '250'
			],
			[
				'name' => 'PVR',
				'bg_color' => '#008196',
				'text_color' => '#ffffff',
				'order' => '250'
			],
			[
				'name' => 'SRP',
				'bg_color' => '#000000',
				'text_color' => '#ffffff',
				'order' => '1000'
			],
			[
				'name' => 'PAP',
				'bg_color' => '#000000',
				'text_color' => '#ffffff',
				'order' => '1000'
			],
			[
				'name' => 'FUN',
				'bg_color' => '#ccff00',
				'text_color' => '#000000',
				'order' => '750'
			],
			[
				'name' => 'ORE',
				'bg_color' => '#76ecff',
				'text_color' => '#000000',
				'order' => '750'
			],
			[
				'name' => 'ICE',
				'bg_color' => '#ffcf76',
				'text_color' => '#000000',
				'order' => '750'
			],
			[
				'name' => 'ROAM',
				'bg_color' => '#f968bf',
				'text_color' => '#000000',
				'order' => '500'
			],
			[
				'name' => 'STRATOP',
				'bg_color' => '#ff7800',
				'text_color' => '#ffffff',
				'order' => '500'
			],
		]);
	}
}
