<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Maicol07\SSO\Flarum;

class UserRegistered
{
    /**
     * The Flarum SSO instance.
     */
    protected $flarum;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // Configure the Flarum SSO instance
        $this->flarum = new Flarum([
            'url' => env('FORUM_URL'), // Set this in your .env file (e.g., https://forum.example.com)
            'root_domain' => env('APP_URL'), // Set to root domain (e.g., example.com)
            'api_key' => env('FORUM_API_KEY'), // Set your Flarum API key in .env
            'password_token' => env('FORUM_PASSWORD_TOKEN'), // Set a secure random string in .env
        ]);
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Use username or fallback to email if name is unavailable
        $username = $user->name ?? $user->email;

        // Create the Flarum user
        $flarumUser = $this->flarum->user($username);

        // Set user details
        $flarumUser->attributes->email = $user->email;

        // Use a random password if you cannot pass a plain-text password
        $password = $user->getAuthPassword(); // Get plain password if stored
        $flarumUser->attributes->password = $password ?? str_random(16); // Generate a fallback password

        // Ensure valid Flarum username (adjust as necessary)
        $flarumUser->attributes->display_name = substr($username, 0, 20); // Flarum limits usernames to 20 chars

        try {
            // Create the user in Flarum and log them in
            $flarumUser->create();
            $flarumUser->login();
        } catch (\Exception $e) {
            // Log any errors
            //Log::error('Flarum SSO sync failed for user ' . $user->email . ': ' . $e->getMessage());
        }
    }
}
