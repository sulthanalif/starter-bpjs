<?php

return [
    'sidebar' => [
        [
            'text' => 'Dashboard',
            'icon' => 'bi bi-speedometer',
            'route' => 'dashboard', // Contoh rute yang aktif
        ],
        [
            'header' => 'Master Data',
        ],
        [
            'text' => 'Auth',
            'icon' => 'bi bi-box-arrow-in-right',
            'active_routes' => ['auth.*'],
            'submenu' => [
                [
                    'text' => 'User',
                    'route' => 'users',
                    'url' => '#',
                ],
                [
                    'text' => 'Version 2',
                    'submenu' => [
                        [
                            'text' => 'Login',
                            'route' => 'auth.v2.login',
                            'url' => asset('dist') . '/examples/login-v2.html',
                        ],
                        [
                            'text' => 'Register',
                            'route' => 'auth.v2.register',
                            'url' => asset('dist') . '/examples/register-v2.html',
                        ],
                    ],
                ],
                [
                    'text' => 'Lockscreen',
                    'route' => 'auth.lockscreen',
                    'url' => asset('dist') . '/examples/lockscreen.html',
                ],
            ],
        ],
    ]
];
