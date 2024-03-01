const API = {
    AUTH: {
        WEB: {
            USER: {
                registration: '/web/auth/user/registration',
                login: '/web/auth/user/login',
                logout: '/web/auth/user/logout'
            },
            WORKER: {
                login: '/web/auth/worker/login',
                logout: '/web/auth/worker/logout',
                'recovery-password': '/web/auth/worker/recovery-password',
            },
            ADMIN: {
                login: '/web/auth/admin/login',
                logout: '/web/auth/admin/logout'
            }
        },
        API: {
            USER: {
                register: '/api/auth/user/register',
                login: '/api/auth/user/login'
            },
            WORKER: {
                login: '/api/auth/worker/login',
                'change-password': '/api/auth/worker/change-password'
            },
            ADMIN: {
                'change-default-admin-info': '/api/auth/admin/change-default-admin-info',
                login: '/api/auth/admin/login'
            }
        }
    },
    USER: {
        WEB: {
            PROFILE: {
                home: '/web/user/profile/home'
            }
        },
        API: {
            PROFILE: {
                get: '/api/user/profile/get',
                'social-networks': {
                    get: '/api/user/profile/social-networks/get'
                }
            },
            ORDER: {
                service: {
                    upcoming: {
                        get: {
                            all: '/api/user/order/service/upcoming/get/all'
                        }
                    },
                    add: '/api/user/order/service/add',
                    cancel: '/api/user/order/service/cancel'
                }
            },
            SERVICE: {
                get: {
                    workers: {
                        all: '/api/user/service/get/workers/all'
                    },
                    all: '/api/user/service/get/all'
                }
            },
            WORKER: {
                get: {
                    services: {
                        all: '/api/user/worker/get/services/all'
                    },
                    all: '/api/user/worker/get/all'
                }
            },
            AFFILIATE: {
                get: {
                    all: '/api/user/affiliate/get/all'
                }
            },
            SCHEDULE: {
                search: '/api/user/schedule/search'
            }
        }
    },
    WORKER: {
        WEB: {
            PROFILE: {
                home: '/web/worker/profile/home',
                settings: '/web/worker/profile/settings',
                schedule: '/web/worker/profile/schedule',
                services: '/web/worker/profile/services',
                pricing: '/web/worker/profile/pricing'
            }
        },
        API: {
            SERVICE: {
                add: '/api/worker/service/add',
                edit: '/api/worker/service/edit',
                delete: '/api/worker/service/delete',
                get: {
                    one: '/api/worker/service/get/one',
                    all: '/api/worker/service/get/all',
                    'all-with-departments': '/api/worker/service/get/all-with-departments'
                }
            },
            AFFILIATE: {
                get: {
                    all: '/api/worker/affiliate/get/all'
                }
            },
            SCHEDULE: {
                add: '/api/worker/schedule/add',
                edit: '/api/worker/schedule/edit',
                delete: '/api/worker/schedule/delete',
                search: '/api/worker/schedule/search',
                get: {
                    one: '/api/worker/schedule/get/one',
                    'busy-time-intervals': '/api/worker/schedule/get/busy-time-intervals',
                    'edit-busy-time-intervals': '/api/worker/schedule/get/edit-busy-time-intervals',
                }
            },
            PROFILE: {
                get: '/api/worker/profile/get',
                'service-pricing': {
                    get: {
                        all: '/api/worker/profile/service-pricing/get/all',
                    },
                    add: '/api/worker/profile/service-pricing/add',
                    edit: '/api/worker/profile/service-pricing/edit',
                    delete: '/api/worker/profile/service-pricing/delete',
                },
                service: {
                    get: {
                        all: '/api/worker/profile/service/get/all'
                    }
                },
                id:  '/api/worker/profile/id',
                'personal-info': {
                    get: '/api/worker/profile/personal-info/get',
                    edit: '/api/worker/profile/personal-info/edit',
                },
                social: {
                    get: {
                        all: '/api/worker/profile/social/get/all',
                    },
                    edit: {
                        all: '/api/worker/profile/social/edit/all',
                    }
                }
            },
            ORDER: {
                service: {
                    cancel: '/api/worker/order/service/cancel',
                    complete: '/api/worker/order/service/complete',
                }
            },
            DEPARTMENT: {
                get: {
                    all: ' /api/worker/department/get/all'
                }
            },
            POSITION: {
                get: {
                    one: '/api/worker/position/get/one'
                }
            },
            ROLE: {
                get: {
                    one: '/api/worker/role/get/one'
                }
            }
        }
    },
    ADMIN: {
        WEB: {
            PROFILE: {
                home: '/web/admin/profile/home',
                workers: '/web/admin/profile/workers',
                services: '/web/admin/profile/services',
                departments: '/web/admin/profile/departments',
                positions: '/web/admin/profile/positions',
                affiliates: '/web/admin/profile/affiliates'
            }
        },
        API: {
            PROFILE: {
                get: '/api/admin/profile/get'
            },
            WORKER: {
                edit: '/api/admin/worker/edit',
                delete: '/api/admin/worker/delete',
                get: {
                    one: '/api/admin/worker/get/one',
                    all: '/api/admin/worker/get/all',
                    'all-limited': '/api/admin/worker/get/all-limited',
                    'all-by-department': '/api/admin/worker/get/all-by-department',
                    'all-by-service': '/api/admin/worker/get/all-by-service'
                },
                register: '/api/admin/worker/register'
            },
            POSITION: {
                add: '/api/admin/position/add',
                edit: '/api/admin/position/edit',
                delete: '/api/admin/position/delete',
                get: {
                    one: '/api/admin/position/get/one',
                    all: '/api/admin/position/get/all',
                    'all-with-departments': '/api/admin/position/get/all-with-departments',
                }
            },
            ROLE: {
                get: {
                    all: ' /api/admin/role/get/all'
                }
            },
            SERVICE: {
                add: '/api/admin/service/add',
                edit: '/api/admin/service/edit',
                delete: '/api/admin/service/delete',
                get: {
                    one:  '/api/admin/service/get/one',
                    'all-with-departments': '/api/admin/service/get/all-with-departments',
                },
            },
            DEPARTMENT: {
                add: '/api/admin/department/add',
                edit: '/api/admin/department/edit',
                delete: '/api/admin/department/delete',
                get: {
                    all: '/api/admin/department/get/all',
                    'all-limited': '/api/admin/department/get/all-limited',
                    'all-services': '/api/admin/department/get/all-services',
                }
            },
            AFFILIATE: {
                add: '/api/admin/affiliate/add',
                edit: '/api/admin/affiliate/edit',
                delete: '/api/admin/affiliate/delete',
                get: {
                    one: '/api/admin/affiliate/get/one',
                    'all-limited': '/api/admin/affiliate/get/all-limited'
                }
            }
        }
    }
};

export default API;
