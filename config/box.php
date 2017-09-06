<?php

return [

    'app' => [
        'id' => env('BOX_APP_ID'),
    ],

    'api' => [
        'key'    => env('BOX_API_KEY'),
        'secret' => env('BOX_API_SECRET'),
    ],

    'auth' => [
        'key_id'           => env('BOX_AUTH_KEY_ID'),
        'private_key_path' => env('BOX_AUTH_PRIVATE_KEY_PATH'),
        'passphrase'       => env('BOX_AUTH_PASSPHRASE'),
    ]

];
