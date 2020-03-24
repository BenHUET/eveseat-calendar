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
                'label' => 'calendar::seat.operations',
                'icon'  => 'fa-space-shuttle',
                'route' => 'operation.index',
                'permission' => 'calendar.view'
            ],
            [
                'name'  => 'Settings',
                'label' => 'calendar::seat.settings',
                'icon'  => 'fa-cog',
                'route' => 'setting.index',
                'permission' => 'calendar.setup'
            ]
        ]
    ]
];
