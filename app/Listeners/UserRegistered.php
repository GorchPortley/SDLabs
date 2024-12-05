<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Maicol07\SSO\User;
use Maicol07\SSO\Flarum;

$flarum = new Flarum([
    'url' => env('FORUM_URL'),
    'root_domain' => env('APP_URL'),
    'api_key' => env('FORUM_API_KEY'),
    'password_token' => env('PASSWORD_TOKEN'),
    'remember' => true,
    'verify_ssl' => env('FORUM_VERIFY_SSL', true),
    'cookies_prefix' => 'flarum',
]);

class UserRegistered
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle($flarum, Registered $event): void
    {
        $user = $event->user;
        $user = $flarum->user($user->name);
        $flarum_user = $flarum->user();
        $flarum_user->attributes->email = $user->email;
        $flarum_user->attributes->password = $user->password;
        $flarum_user->signup();
        $flarum_user->login();
    }
}
