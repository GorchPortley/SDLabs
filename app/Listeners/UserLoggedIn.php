<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Maicol07\SSO\Flarum;

class UserLoggedIn
{
    public function handle(Login $event)
    {
        $user = $event->user;

            $flarum = new Flarum([
                'url' => env('FORUM_URL'),
                'root_domain' => env('APP_URL'),
                'api_key' => env('FORUM_API_KEY'),
                'password_token' => env('FORUM_PASSWORD_TOKEN'),
                'remember' => true,
                'verify_ssl' => env('FORUM_VERIFY_SSL', true),
            ]);

            // Create user in Flarum
            $flarum_user = $flarum->user($user->email);
            $flarum_user->attributes->username = $user->username ?? $user->name;
            $flarum_user->attributes->email = $user->email;
            $flarum_user->attributes->password = $user->password;

            $flarum_user->signup();
            $flarum_user->login();
    }
}
