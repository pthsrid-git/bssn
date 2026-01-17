<?php

return [
    'api' => [
        'auth' => [
            'internal' => env('API_AUTH_INTERNAL', null),
            'role' => [
                'admin-eperforma' => 'logbook.admin.eperforma',
                'pko' => 'logbook.pko',
                'pmk' => 'logbook.pmk',
                'admin' => 'logbook.admin',
            ],
        ],
        'master_data' => [
            'url' => env('API_URL_MASTER_DATA', null),
            'key' => env('API_KEY_MASTER_DATA', null),
        ],
    ],
];
