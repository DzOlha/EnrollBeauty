<?php

const OPEN_PAGES = [
    'index' => [
        'path' =>  VIEW_OPEN_PAGES .'/'. 'index',
        'data' => [
            'title' => 'Homepage'
        ]
    ],

    'error' => [
        'path' => VIEW_OPEN_PAGES .'/' . 'error/error',
        'data' => [
            'title' => 'Page Not Found',
            'message' => 'The requested page not found!'
        ]
    ]
];

const ADMIN_PAGES = [
    'change_default' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/auth/change_default',
        'data' => [
            'title' => 'Change Admin Info'
        ]
    ],

    'home' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/home',
        'data' => [
            'title'     => 'Admin Account',
            'page_name' => 'Homepage'
        ]
    ],

    'workers' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/worker/workers',
        'data' =>  [
            'title'     => 'User Management',
            'page_name' => 'Workers'
        ]
    ],

    'services' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/service/services',
        'data' => [
            'title'     => 'Service Management',
            'page_name' => 'Services'
        ]
    ],

    'departments' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/department/departments',
        'data' => [
            'title'     => 'Department Management',
            'page_name' => 'Departments'
        ]
    ],

    'positions' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/position/positions',
        'data' => [
            'title'     => 'Position Management',
            'page_name' => 'Positions'
        ]
    ],

    'affiliates' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/affiliate/affiliates',
        'data' => [
            'title'     => 'Affiliate Management',
            'page_name' => 'Affiliates'
        ]
    ],

    'login' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'admin/pages/auth/login',
        'data' => [
            'title' => 'Login | Admin'
        ]
    ]
];

const WORKER_PAGES = [
    'login' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/auth/login',
        'data' => [
            'title' => 'Login | Worker'
        ]
    ],

    'change_password' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/auth/change_password',
        'data' => [
            'title' => 'Change Password'
        ]
    ],

    'home' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/home',
        'data' => [
            'title' => 'Worker Account',
            'page_name' => 'Homepage'
        ]
    ],

    'settings' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/settings/settings',
        'data' => [
            'title' => 'Worker Account',
            'page_name' => 'Profile Settings'
        ]
    ],

    'schedule' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/schedule/schedule',
        'data' => [
            'title' => 'Schedule Management',
            'page_name' => 'Schedule'
        ]
    ],

    'services' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/service/services',
        'data' => [
            'title' => 'Service Management',
            'page_name' => 'Services'
        ]
    ],

    'pricing' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/pricing/pricing',
        'data' => [
            'title' => 'Service Pricing',
            'page_name' => 'Price-list'
        ]
    ],

    'public_profile' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'worker/pages/profile/public/public_profile',
        'data' => [
            'title' => 'Worker Profile',
            'page_num' => ''
        ]
    ]
];

const USER_PAGES = [
    'registration' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'user/pages/auth/registration',
        'data' => [
            'title' => 'Registration'
        ]
    ],

    'login' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'user/pages/auth/login',
        'data' => [
            'title' => 'Login'
        ]
    ],

    'home' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'user/pages/home',
        'data' => [
            'title' => 'Account',
            'page_name' => 'Homepage'
        ]
    ],

    'settings' => [
        'path' => VIEW_PROTECTED_PAGES .'/' . 'user/pages/settings/settings',
        'data' => [
            'title' => 'Account Settings',
            'page_name' => 'Settings'
        ]
    ]
];