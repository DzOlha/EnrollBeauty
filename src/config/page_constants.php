<?php

const COMMON_PAGES = [
    'index' => [
        'path' =>  VIEW_FRONTEND . 'index',
        'data' => [
            'title' => 'Homepage'
        ]
    ],

    'error' => [
        'path' => VIEW_FRONTEND . 'pages/system/error',
        'data' => [
            'title' => 'Page Not Found',
            'message' => 'The requested page not found!'
        ]
    ]
];

const ADMIN_PAGES = [
    'change_default' => [
        'path' => VIEW_FRONTEND . 'pages/admin/forms/change_default',
        'data' => [
            'title' => 'Change Admin Info'
        ]
    ],

    'home' => [
        'path' => VIEW_FRONTEND . 'pages/admin/profile/home',
        'data' => [
            'title'     => 'Admin Account',
            'page_name' => 'Homepage'
        ]
    ],

    'workers' => [
        'path' => VIEW_FRONTEND . 'pages/admin/profile/workers',
        'data' =>  [
            'title'     => 'User Management',
            'page_name' => 'Workers'
        ]
    ],

    'services' => [
        'path' => VIEW_FRONTEND . 'pages/admin/profile/services',
        'data' => [
            'title'     => 'Service Management',
            'page_name' => 'Services'
        ]
    ],

    'departments' => [
        'path' => VIEW_FRONTEND . 'pages/admin/profile/departments',
        'data' => [
            'title'     => 'Department Management',
            'page_name' => 'Departments'
        ]
    ],

    'positions' => [
        'path' => VIEW_FRONTEND . 'pages/admin/profile/positions',
        'data' => [
            'title'     => 'Position Management',
            'page_name' => 'Positions'
        ]
    ],

    'login' => [
        'path' => VIEW_FRONTEND . 'pages/admin/forms/login',
        'data' => [
            'title' => 'Login | Admin'
        ]
    ]
];

const WORKER_PAGES = [
    'login' => [
        'path' => VIEW_FRONTEND . 'pages/worker/forms/login',
        'data' => [
            'title' => 'Login | Worker'
        ]
    ],

    'change_password' => [
        'path' => VIEW_FRONTEND . 'pages/worker/forms/change_password',
        'data' => [
            'title' => 'Change Password'
        ]
    ],

    'home' => [
        'path' => VIEW_FRONTEND . 'pages/worker/profile/home',
        'data' => [
            'title' => 'Worker Account',
            'page_name' => 'Homepage'
        ]
    ],

    'schedule' => [
        'path' => VIEW_FRONTEND . 'pages/worker/profile/schedule',
        'data' => [
            'title' => 'Schedule Management',
            'page_name' => 'Schedule'
        ]
    ],

    'services' => [
        'path' => VIEW_FRONTEND . 'pages/worker/profile/services',
        'data' => [
            'title' => 'Service Management',
            'page_name' => 'Services'
        ]
    ],

    'pricing' => [
        'path' => VIEW_FRONTEND . 'pages/worker/profile/pricing',
        'data' => [
            'title' => 'Service Pricing',
            'page_name' => 'Price-list'
        ]
    ]
];

const USER_PAGES = [
    'registration' => [
        'path' => VIEW_FRONTEND . 'pages/user/forms/registration',
        'data' => [
            'title' => 'Registration'
        ]
    ],

    'login' => [
        'path' => VIEW_FRONTEND . 'pages/user/forms/login',
        'data' => [
            'title' => 'Login'
        ]
    ],

    'home' => [
        'path' => VIEW_FRONTEND . 'pages/user/profile/home',
        'data' => [
            'title' => 'Account',
            'page_name' => 'Homepage'
        ]
    ]
];