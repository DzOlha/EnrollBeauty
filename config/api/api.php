<?php
const API = [
    'AUTH'   => [
        'WEB' => [
            'USER'   => [
                'registration' => '/web/auth/user/registration',
                'login'        => '/web/auth/user/login',
                'logout'       => '/web/auth/user/logout'
            ],
            'WORKER' => [
                'login'             => '/web/auth/worker/login',
                'logout'            => '/web/auth/worker/logout',
                'recovery-password' => '/web/auth/worker/recovery-password'
            ],
            'ADMIN'  => [
                'login'  => '/web/auth/admin/login',
                'logout' => '/web/auth/admin/logout'
            ]
        ],
        'API' => [ // POST
                   'USER'   => [
                       'register' => '/api/auth/user/register',
                       'login'    => '/api/auth/use/login'
                   ],
                   'WORKER' => [
                       'login'           => '/api/auth/worker/login',
                       'change-password' => '/api/auth/worker/change-password'
                   ],
                   'ADMIN'  => [
                       'change-default-admin-info' => '/api/auth/admin/change-default-admin-info',
                       'login'                     => '/api/auth/admin/login'
                   ]
        ]
    ],
    'USER'   => [
        'WEB' => [
            'PROFILE' => [
                'home'     => '/web/user/profile/home',
                'settings' => '/web/user/profile/settings',
                'orders'   => '/web/user/profile/orders'
            ]
        ],
        'API' => [
            'PROFILE'   => [
                'get'             => '/api/user/profile/get', // GET
                'social-networks' => [
                    'get'  => '/api/user/profile/social-networks/get', // GET
                    'edit' => '/api/user/profile/social-networks/edit', // PUT
                ],
                'id'              => '/api/user/profile/id', // GET
                'personal-info'   => [
                    'get'  => '/api/user/profile/personal-info/get', // GET
                    'edit' => '/api/user/profile/personal-info/edit', // POST
                ],
            ],
            'ORDER'     => [
                'service' => [
                    'upcoming' => [
                        'get' => [ // GET
                                   'all' => '/api/user/order/service/upcoming/get/all'
                        ]
                    ],
                    'get'      => [
                        'all-limited' => '/api/user/order/service/get/all-limited'
                    ],
                    'add'      => '/api/user/order/service/add', // POST
                    'cancel'   => '/api/user/order/service/cancel' // POST
                ]
            ],
            'SERVICE'   => [
                'get' => [ // GET
                           'workers' => [
                               'all' => '/api/user/service/get/workers/all'
                           ],
                           'all'     => '/api/user/service/get/all'
                ]
            ],
            'WORKER'    => [
                'get' => [ // GET
                           'services' => [
                               'all' => '/api/user/worker/get/services/all'
                           ],
                           'all'      => '/api/user/worker/get/all'
                ],
            ],
            'AFFILIATE' => [
                'get' => [ // GET
                           'all' => '/api/user/affiliate/get/all'
                ]
            ],
            'SCHEDULE'  => [
                'search' => '/api/user/schedule/search' // POST
            ]
        ]
    ],
    'WORKER' => [
        'WEB' => [
            'PROFILE' => [
                'home'     => '/web/worker/profile/home',
                'settings' => '/web/worker/profile/settings',
                'schedule' => '/web/worker/profile/schedule',
                'services' => '/web/worker/profile/services',
                'pricing'  => '/web/worker/profile/pricing',
                'orders'   => '/web/worker/profile/orders'
            ]
        ],
        'API' => [
            'SERVICE'    => [
                'add'    => '/api/worker/service/add', // POST
                'edit'   => '/api/worker/service/edit', // PUT
                'delete' => '/api/worker/service/delete', // DELETE
                'get'    => [ // GET
                              'one'                  => '/api/worker/service/get/one',
                              'all'                  => '/api/worker/service/get/all',
                              'all-with-departments' => '/api/worker/service/get/all-with-departments',
                              'all-by-worker'        => '/api/worker/service/get/all-by-worker',
                ]
            ],
            'AFFILIATE'  => [
                'get' => [ // GET
                           'all' => '/api/worker/affiliate/get/all'
                ]
            ],
            'SCHEDULE'   => [
                'add'    => '/api/worker/schedule/add', // POST
                'edit'   => '/api/worker/schedule/edit', // PUT
                'delete' => '/api/worker/schedule/delete', // DELETE
                'search' => '/api/worker/schedule/search', // POST
                'get'    => [ // GET
                              'one'                      => '/api/worker/schedule/get/one',
                              'busy-time-intervals'      => '/api/worker/schedule/get/busy-time-intervals',
                              'edit-busy-time-intervals' => '/api/worker/schedule/get/edit-busy-time-intervals',
                ]
            ],
            'PROFILE'    => [
                'get'             => '/api/worker/profile/get', // GET
                'service-pricing' => [
                    'get'    => [ // GET
                                  'all' => '/api/worker/profile/service-pricing/get/all',
                    ],
                    'add'    => '/api/worker/profile/service-pricing/add', // POST
                    'edit'   => '/api/worker/profile/service-pricing/edit', // PUT
                    'delete' => '/api/worker/profile/service-pricing/delete' // DELETE
                ],
                'service'         => [
                    'get' => [ // GET
                               'all' => '/api/worker/profile/service/get/all'
                    ]
                ],
                'id'              => '/api/worker/profile/id', // GET
                'personal-info'   => [
                    'get'  => '/api/worker/profile/personal-info/get', // GET
                    'edit' => '/api/worker/profile/personal-info/edit', // POST
                ],
                'social'          => [
                    'get'  => [ // GET
                                'all' => '/api/worker/profile/social/get/all',
                    ],
                    'edit' => [ // PUT
                                'all' => '/api/worker/profile/social/edit/all',
                    ],
                ]
            ],
            'ORDER'      => [
                'service' => [ // POST
                               'cancel'   => '/api/worker/order/service/cancel',
                               'complete' => '/api/worker/order/service/complete',
                               'get'      => [
                                   'all-limited' => '/api/worker/order/service/get/all-limited'
                               ],
                ]
            ],
            'DEPARTMENT' => [
                'get' => [ // GET
                           'all'           => ' /api/worker/department/get/all',
                           'all-by-worker' => '/api/worker/department/get/all-by-worker',
                ]
            ],
            'POSITION'   => [
                'get' => [ // GET
                           'one' => '/api/worker/position/get/one'
                ]
            ],
            'ROLE'       => [
                'get' => [ // GET
                           'one' => '/api/worker/role/get/one'
                ]
            ],
        ]
    ],
    'ADMIN'  => [
        'WEB' => [
            'PROFILE' => [
                'home'        => '/web/admin/profile/home',
                'workers'     => '/web/admin/profile/workers',
                'services'    => '/web/admin/profile/services',
                'departments' => '/web/admin/profile/departments',
                'positions'   => '/web/admin/profile/positions',
                'affiliates'  => '/web/admin/profile/affiliates',
                'orders'      => '/web/admin/profile/orders'
            ]
        ],
        'API' => [
            'PROFILE'    => [
                'get' => '/api/admin/profile/get' // GET
            ],
            'WORKER'     => [
                'edit'     => '/api/admin/worker/edit', // POST
                'delete'   => '/api/admin/worker/delete', // DELETE
                'get'      => [ // GET
                                'one'               => '/api/admin/worker/get/one',
                                'all'               => '/api/admin/worker/get/all',
                                'all-limited'       => '/api/admin/worker/get/all-limited',
                                'all-by-department' => '/api/admin/worker/get/all-by-department',
                                'all-by-service'    => '/api/admin/worker/get/all-by-service'
                ],
                'register' => '/api/admin/worker/register' // POST
            ],
            'POSITION'   => [
                'add'    => '/api/admin/position/add', // POST
                'edit'   => '/api/admin/position/edit', // PUT
                'delete' => '/api/admin/position/delete', // DELETE
                'get'    => [ // GET
                              'one'                  => '/api/admin/position/get/one',
                              'all'                  => '/api/admin/position/get/all',
                              'all-with-departments' => '/api/admin/position/get/all-with-departments',
                ]
            ],
            'ROLE'       => [
                'get' => [ // GET
                           'all' => '/api/admin/role/get/all'
                ]
            ],
            'SERVICE'    => [
                'add'    => '/api/admin/service/add', // POST
                'edit'   => '/api/admin/service/edit', // PUT
                'delete' => '/api/admin/service/delete', // DELETE
                'get'    => [ // GET
                              'one'                  => '/api/admin/service/get/one',
                              'all'                  => '/api/admin/service/get/all',
                              'all-with-departments' => '/api/admin/service/get/all-with-departments',
                ],
            ],
            'DEPARTMENT' => [
                'add'    => '/api/admin/department/add', // POST
                'edit'   => '/api/admin/department/edit', // POST
                'delete' => '/api/admin/department/delete', // DELETE
                'get'    => [ // GET
                              'all'          => '/api/admin/department/get/all',
                              'one'          => '/api/admin/department/get/one',
                              'all-limited'  => '/api/admin/department/get/all-limited',
                              'all-services' => '/api/admin/department/get/all-services',
                ]
            ],
            'AFFILIATES' => [
                'add'    => '/api/admin/affiliate/add', // POST
                'edit'   => '/api/admin/affiliate/edit', // PUT
                'delete' => '/api/admin/affiliate/delete', // DELETE
                'get'    => [ // GET
                              'one'         => '/api/admin/affiliate/get/one',
                              'all'         => '/api/admin/affiliate/get/all',
                              'all-limited' => '/api/admin/affiliate/get/all-limited'
                ]
            ],
            'ORDER'      => [
                'service' => [
                    'get'      => [
                        'all-limited' => '/api/admin/order/service/get/all-limited',
                    ],
                    'delete'   => '/api/admin/order/service/delete',
                    'complete' => '/api/admin/order/service/complete',
                    'cancel'   => '/api/admin/order/service/cancel',
                ]
            ],
            'USER'       => [
                'get' => [
                    'all-by-email' => '/api/admin/user/get/all-by-email'
                ]
            ]
        ]
    ],
    'OPEN'   => [
        'WEB' => [
            'WORKER' => [
                'profile' => '/web/open/worker/profile/{name-surname-id}'
            ],
            'USER'   => [
                'profile' => '/web/open/user/profile'
            ]
        ],
        'API' => [
            'WORKER'     => [
                'profile' => [
                    'get' => [ // GET
                               'one' => '/api/open/worker/profile/get/one'
                    ]
                ],
                'get'     => [ // GET
                               'services'    => [
                                   'all' => '/api/open/worker/get/services/all'
                               ],
                               'all'         => '/api/open/worker/get/all',
                               'all-limited' => '/api/open/worker/get/all-limited',
                ]
            ],
            'SERVICE'    => [
                'PRICING' => [
                    'get' => [ // GET
                               'all' => '/api/open/service/pricing/get/all'
                    ]
                ],
                'get'     => [ // GET
                               'workers' => [
                                   'all' => '/api/open/service/get/workers/all'
                               ],
                               'all'     => '/api/open/service/get/all'
                ]
            ],
            'AFFILIATE'  => [
                'get' => [ // GET
                           'all' => '/api/open/affiliate/get/all'
                ]
            ],
            'SCHEDULE'   => [
                'search' => '/api/open/schedule/search' // POST
            ],
            'ORDER'      => [
                'service' => [
                    'add' => '/api/user/order/service/add' // POST,  just use the UserApiController here
                ]
            ],
            'DEPARTMENT' => [
                'get' => [ // GET
                           'all-limited' => '/api/open/department/get/all-limited'
                ]
            ]
        ]
    ]
];