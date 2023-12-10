const API = {
    USER: {
        WEB: {
            AUTH: {
                registration: '/web/user/auth/registration',
                login: '/web/user/auth/login',
                logout: '/web/user/auth/logout'
            },
            PROFILE: {
                home: '/web/user/profile/home'
            }
        },
        API: {
            AUTH: {
                register: '/api/user/auth/register',
                login: '/api/user/auth/login'
            },
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
            AUTH: {
                login: '/web/worker/auth/login',
                'recovery-password': '/web/worker/auth/recovery-password',
                logout: '/web/worker/auth/logout'
            },
            PROFILE: {
                home: '/web/worker/profile/home',
                schedule: '/web/worker/profile/schedule',
                services: '/web/worker/profile/services',
                pricing: '/web/worker/profile/pricing'
            }
        },
        API: {
            AUTH: {
                login: '/api/worker/auth/login',
                'change-password': '/api/worker/auth/change-password'
            },
            SERVICE: {
                add: '/api/worker/service/add',
                get: {
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
                search: '/api/worker/schedule/search',
                get: {
                    'busy-time-intervals': '/api/worker/schedule/get/busy-time-intervals'
                }
            },
            PROFILE: {
                get: '/api/worker/profile/get',
                'service-pricing': {
                    get: {
                        all: '/api/worker/profile/service-pricing/get/all',
                    },
                    add: '/api/worker/profile/service-pricing/add',
                    edit: '/api/worker/profile/service-pricing/edit'
                },
                service: {
                    get: {
                        all: '/api/worker/profile/service/get/all'
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
            }
        }
    },
    ADMIN: {
        WEB: {
            AUTH: {
                login: '/web/admin/auth/login',
                logout: '/web/admin/auth/logout'
            },
            PROFILE: {
                home: '/web/admin/profile/home',
                workers: '/web/admin/profile/workers'
            }
        },
        API: {
            AUTH: {
                'change-default-admin-info': '/api/admin/auth/change-default-admin-info',
                login: '/api/admin/auth/login'
            },
            PROFILE: {
                get: '/api/admin/profile/get'
            },
            WORKER: {
                get: {
                    all: '/api/admin/worker/get/all'
                },
                register: '/api/admin/worker/register'
            },
            POSITION: {
                get: {
                    all: '/api/admin/position/get/all'
                }
            },
            ROLE: {
                get: {
                    all: ' /api/admin/role/get/all'
                }
            },
            SERVICE: {
                get: {
                    'all-with-departments': '/api/admin/service/get/all',
                },
                add: '/api/admin/service/add'
            }
        }
    }
};

export default API;
