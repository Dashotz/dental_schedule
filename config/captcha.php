<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], // Numbers only
    'fontsDirectory' => base_path('vendor/mews/captcha/assets/fonts'),
    'bgsDirectory' => base_path('vendor/mews/captcha/assets/backgrounds'),
    'default' => [
        'length' => 6,
        'width' => 345,
        'height' => 65,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
    ],
    'flat' => [
        'length' => 6,
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'width' => 345,
        'height' => 65,
        'math' => false,
        'quality' => 100,
        'lines' => 6,
        'bgImage' => false,
        'bgColor' => '#ecf0f1',
        'contrast' => 0,
        'sensitive' => false, // Case insensitive (not needed for numbers)
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => false,
        'contrast' => -5,
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
    ],
];
