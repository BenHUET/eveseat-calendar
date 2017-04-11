<?php

return [
	'calendar' => [
		'name'          => 'calendar',
		'label'         => 'calendar::seat.plugin_name',
		'icon'          => 'fa-calendar',
		'route_segment' => 'calendar',
		'permission' => 'calendar.view',
		'entries' => [
			[
				'name'  => 'Operations',
				'icon'  => 'fa-space-shuttle',
				'route' => 'operation.index',
				'permission' => 'calendar.view'
			],
			[
				'name'  => 'Settings',
				'icon'  => 'fa-cog',
				'route' => 'setting.index',
				'permission' => 'calendar.setup'
			]
		]
	]
];