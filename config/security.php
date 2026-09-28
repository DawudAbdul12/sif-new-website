<?php

return [
    'rate_limits' => [
        'public' => env('RATE_LIMIT_PUBLIC_PER_MINUTE', 120),
        'admin_auth' => env('RATE_LIMIT_ADMIN_AUTH_PER_MINUTE', 5),
        'admin' => env('RATE_LIMIT_ADMIN_PER_MINUTE', 120),
        'admin_write' => env('RATE_LIMIT_ADMIN_WRITE_PER_MINUTE', 60),
        'admin_upload' => env('RATE_LIMIT_ADMIN_UPLOAD_PER_MINUTE', 20),
        'admin_import' => env('RATE_LIMIT_ADMIN_IMPORT_PER_MINUTE', 5),
    ],
];
