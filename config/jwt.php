<?php

return [
    'public' => storage_path(env('JWT_PUBLIC_KEY')),
    'private' => storage_path(env('JWT_PRIVATE_KEY')),
    'algorithm' => env('JWT_ALGO'),
];
