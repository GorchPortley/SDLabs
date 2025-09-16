<?php
namespace App\Listeners;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $this->createUser($event);
    }
    public function createUser(Registered $event)
    {
        $user = $event->user;
        $randomPassword = Str::random(16);
        $user->forum_password = $randomPassword;
        $user->save();
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . env('API_KEY') . '; userId=1'
        ])->post(env('FLARUM_HOST') . '/api/users', [
            'data' => [
                'type' => 'users',
                'attributes' => [
                    'username' => $user->username,
                    'email' => $user->email,
                    'password' => $randomPassword,
                    'isEmailConfirmed' => true
                ]
            ]
        ]);
        
        Log::info('Flarum user creation response', ['response' => $response->json()]);
        
        return $response->json();
    }
}