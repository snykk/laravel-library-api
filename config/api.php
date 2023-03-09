<?php

return [

    'cms_url'         => env('CMS_URL', 'http://localhost:3000'),
    'cms_guard'       => 'cms-api',
    'cms_path_prefix' => 'cms-api/',
    'api_guard'       => 'api',

    'captcha_enabled' => env('CAPTCHA_ENABLED', false),
];
