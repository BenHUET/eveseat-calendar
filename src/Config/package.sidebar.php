<?php

return [
    'calendar' => [
        'name'          => 'calendar',
        'label'         => 'calendar::seat.plugin_name',
        'icon'          => 'fas fa-calendar-alt',
        'route_segment' => 'calendar',
        'permission' => 'calendar.view',
        'entries' => [
            [
                'name'  => 'Operations',
                'label' => 'calendar::seat.operations',
                'icon'  => 'fas fa-calendar-day',
                'route' => 'operation.index',
                'permission' => 'calendar.view'
            ],
            [
                'name'  => 'Settings',
                'label' => 'calendar::seat.settings',
                'icon'  => 'fas fa-cog',
                'route' => 'setting.index',
                'permission' => 'calendar.setup'
            ]
        ]
    ]
];
