<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'administrator' => [
            'roles' => 'c,r,u,d',
            'permissions' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'urls' => 'c,r,u,d',
        ],
        'user' => [
            'users' => 'r',
            'urls' => 'c,r',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
