<?php

return [
    'site' => [
        'slack_icon_url' => env('APP_URL'). '/img/slack/1477025458.png',
        'email_logo_url' => env('APP_URL'). '/img/email/1477025458.png',
        'registrations_channel' => '#registrations',
        'notifications_webhook' => env('SLACK_NOTIFICATIONS_WEBHOOK'),
    ]
];
