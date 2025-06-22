<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'database'),

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    'encrypt' => env('SESSION_ENCRYPT', false),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    'path' => env('SESSION_PATH', '/'),

    // ✅ Updated for ngrok
    'domain' => env('SESSION_DOMAIN', '.ngrok-free.app'),

    // ✅ Secure cookies when using HTTPS
    'secure' => env('SESSION_SECURE_COOKIE', true),

    // ✅ HTTP-only to prevent JS access to cookies
    'http_only' => env('SESSION_HTTP_ONLY', true),

    // ✅ Important for cross-site cookie with HTTPS
    'same_site' => env('SESSION_SAME_SITE', 'none'),

    // Optional: Tie cookie to top-level site if needed
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
