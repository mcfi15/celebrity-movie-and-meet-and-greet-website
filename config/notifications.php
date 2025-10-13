<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Email Configuration
    |--------------------------------------------------------------------------
    |
    | This option defines the email address that will receive all activity
    | notifications from the inventory management system.
    |
    */

    'email' => env('NOTIFICATION_EMAIL', 'admin@inventory.local'),

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure which activities should send notifications
    |
    */

    'enabled' => env('NOTIFICATIONS_ENABLED', true),

    'activities' => [
        'product_created' => true,
        'product_updated' => true,
        'product_deleted' => true,
        'category_created' => true,
        'category_updated' => true,
        'category_deleted' => true,
        'supplier_created' => true,
        'supplier_updated' => true,
        'supplier_deleted' => true,
        'request_created' => true,
        'request_updated' => true,
        'request_deleted' => true,
        'user_created' => true,
        'user_updated' => true,
        'user_deleted' => true,
        'user_login' => env('NOTIFY_USER_LOGIN', false),
        'user_logout' => env('NOTIFY_USER_LOGOUT', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Template Settings
    |--------------------------------------------------------------------------
    |
    | Configure the appearance and content of notification emails
    |
    */

    'template' => [
        'from_name' => env('NOTIFICATION_FROM_NAME', 'Inventory Management System'),
        'from_email' => env('NOTIFICATION_FROM_EMAIL', 'noreply@inventory.local'),
        'logo_url' => env('NOTIFICATION_LOGO_URL', null),
        'company_name' => env('NOTIFICATION_COMPANY_NAME', 'Your Company'),
    ],
];