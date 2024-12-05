<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Maicol07\SSO\Flarum;

class UserRegistered
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $flarum = new Flarum([
            'url' => env('FORUM_URL'),
            'root_domain' => env('APP_URL'),
            'api_key' => env('FORUM_API_KEY'),
            'password_token' => env('PASSWORD_TOKEN'),
            'remember' => true,
            'verify_ssl' => env('FORUM_VERIFY_SSL', true),
            'cookies_prefix' => 'flarum',
        ]);

        $user = $flarum->user($event->user->getAuthIdentifier());
        $user->attributes->password = $event->user->getAuthPassword();
        $user->signup();
        $user->login();
    }
}
