<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Maicol07\SSO\Flarum;

class UserLoggedIn
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        $flarum = new Flarum([
            'url' => env('FORUM_URL'),
            'root_domain' => env('APP_URL'),
            'api_key' => env('FORUM_API_KEY'),
            'password_token' => env('FORUM_PASSWORD_TOKEN'),
            'remember' => true,
            'verify_ssl' => env('FORUM_VERIFY_SSL', true),
            'cookies_prefix' => 'flarum',
        ]);

        try {
            $flarum_user = $flarum->user($user->email); // Use email as identifier
            $flarum_user->attributes->email = $user->email;
            $flarum_user->attributes->password = $user->password; // Be cautious with password handling
            $flarum_user->signup();
            $flarum_user->login();
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            \Log::error('Flarum SSO Error: ' . $e->getMessage());
        }
    }
}
