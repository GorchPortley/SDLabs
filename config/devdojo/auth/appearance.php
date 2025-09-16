<?php

/*
 * Branding configs for your application
 */

return [
    'logo' => [
        'type' => 'svg',
        'image_src' => '/storage/app/public/auth/favicon.png',
        'svg_string' => '<svg xmlns="http://www.w3.org/2000/svg" data-v-423bf9ae="" viewBox="0 0 90 90" class="iconLeft"><g data-v-423bf9ae="" id="e41cccc6-1193-4ea8-aef3-65649d67ba6c" transform="matrix(2.8125,0,0,2.8125,0,0)" stroke="none" fill="#000000"><circle cx="16" cy="16.021" r="8.066"/><path d="M32 0H0v32h32V0zM16 26.086c-5.551 0-10.066-4.516-10.066-10.065 0-5.55 4.516-10.066 10.066-10.066 5.55 0 10.065 4.516 10.065 10.066.001 5.55-4.515 10.065-10.065 10.065z"/></g></svg>',
        'height' => '90',
    ],
    'background' => [
        'color' => '#3B0764',
        'image' => '/storage/auth/background.jpg',
        'image_overlay_color' => '#3B0764',
        'image_overlay_opacity' => '1',
    ],
    'color' => [
        'text' => '#000000',
        'button' => '#7121B4',
        'button_text' => '#ffffff',
        'input_text' => '#00134d',
        'input_border' => '#232329',
    ],
    'alignment' => [
        'heading' => 'center',
        'container' => 'center',
    ],
    'favicon' => [
        'light' => '/storage/auth/favicon-dark.png',
        'dark' => '/storage/auth/favicon.png',
    ],
];
