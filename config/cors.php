<?php

return [

    'paths' => ['api/*', 'login', 'logout', 'register', 'sanctum/csrf-cookie', 'users'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
