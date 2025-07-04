<?php
// config/menu.php
return [
    [
        'label' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'route' => 'dashboard',
        'permission' => 'dashboard',
        'active_patterns' => 'admin/dashboard*', // String tunggal
    ],
    [
        'label' => 'Master Data',
        'icon' => 'fas fa-database',
        'route' => 'master.index',
        'permission' => 'master-data',
        'active_patterns' => ['admin/users*', 'admin/role-permission*'],
        'children' => [
            [
                'label' => 'Users',
                'icon' => 'fas fa-users',
                'route' => 'users',
                'permission' => 'manage-users',
                'active_patterns' => 'admin/users*',
            ],
            [
                'label' => 'Role & Permission',
                'icon' => 'fas fa-user-tag',
                'route' => 'role-permission.index',
                'permission' => 'manage-roles',
                'active_patterns' => 'admin/role-permission*',
            ],
        ]
    ],
    [
        'label' => 'Log Viewer',
        'icon' => 'fas fa-flag',
        'url' => '/log-viewer', // Menggunakan 'url' bukan 'route'
        'permission' => 'log-viewer',
        'target' => '_blank', // Akan membuka di tab baru
        'active_patterns' => 'log-viewer*', // Cocokkan URL path
    ],

    // tambahkan item lain sesuai kebutuhan…
];
