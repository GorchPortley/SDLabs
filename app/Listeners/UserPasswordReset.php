<?php
namespace App\Listeners;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class UserPasswordReset
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
    public function handle(PasswordReset $event): void
    {
        $this->resetPassword($event);
    }
    public function resetPassword(PasswordReset $event)
    {
        $user = $event->user;
        
        // First, find the Flarum user ID by email
        $userLookup = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . config('forum.api') . '; userId=1'
        ])->get(config('forum.host') . '/api/users', [
            'filter' => [
                'email' => $user->email
            ]
        ]);
        
        $userData = $userLookup->json();
        
        // Check if user found
        if (empty($userData['data'])) {
            return null; // User not found in Flarum
        }
        
        $flarumUserId = $userData['data'][0]['id'];
        
        // Now change the password
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . config('forum.api') . '; userId=1'
        ])->post(config('forum.host') . "/api/users/{$flarumUserId}/changePassword", [
            'data' => [
                'type' => 'users',
                'id' => $flarumUserId,
                'attributes' => [
                    'password' => $user->password
                ]
            ]
        ]);
        
        return $response->json();
    }
}