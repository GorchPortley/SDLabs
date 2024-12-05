<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Maicol07\SSO\Flarum;

class UserLoggedIn
{
    public function handle(Login $event)
    {
        $user = $event->user;

        try {
            $flarum = new Flarum([
                'url' => env('FORUM_URL'),
                'root_domain' => env('APP_URL'),
                'api_key' => env('FORUM_API_KEY'),
                'password_token' => env('FORUM_PASSWORD_TOKEN'),
                'remember' => true,
                'verify_ssl' => env('FORUM_VERIFY_SSL', true),
                'cookies_prefix' => 'flarum',
            ]);

            // Ensure user exists in Flarum
            $flarum_user = $flarum->user($user->email);

            // Perform Flarum login
            $flarum_user->login();

            \Log::info("Flarum SSO login successful for user: {$user->email}");
        } catch (\Exception $e) {
            \Log::error("Flarum SSO Login Error: " . $e->getMessage());
        }
    }
}
