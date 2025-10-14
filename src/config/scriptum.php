<?php
return [

    'setup' => [
        'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
        'admin_name' => env('ADMIN_NAME', 'Scriptum Admin'),
        'admin_handle' => env('ADMIN_HANDLE', 'scriptum-admin'),
        'admin_password' => env('ADMIN_PASSWORD', 'PlEaSe_ChAnGe_Me'),
        'admin_hashed_password' => env('ADMIN_HASHED_PASSWORD', '$2y$12$hvZDd3rRP.ZjZE04cQ05h.QCswr0PXybcpLVFuE5CSDCXKhOuucui'),
    ],
    'debug' => [
        'dump_sql_queries' => env('DUMP_SQL_QUERIES', false),
    ],
    'show_logo' => env('SHOW_LOGO', false),
    'production' => [
        'domain' => env('DOMAIN', 'localhost'),
        'admin_domain' => env('ADMIN_DOMAIN', 'admin.localhost'),
    ]


];