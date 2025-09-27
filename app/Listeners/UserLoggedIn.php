<?php
namespace App\Listeners;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserLoggedIn
{
    public function handle(Login $event)
    {
        // Define the user variable first
        $user = $event->user;
        
        // Log which user is being processed
        Log::info("Processing login for user", [
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username
        ]);
        
        // Only create a user if forum_password is empty
        if (empty($user->forum_password)) {
            Log::info("User has no forum password, creating new forum user");
            $this->createUser($event);
        } else {
            Log::info("User already has forum password, skipping creation");
        }
        
        // This part is common to both branches, so it shouldn't be duplicated
        $token = $this->getToken($event);
        
        if ($token) {
            $lifetime = 60 * 24 * 14;
            $loginResponse = $this->loginUser($token, $event);
            
            // Log the login response
            Log::info('Flarum login attempt result', [
                'success' => isset($loginResponse['token']),
                'response_data' => $loginResponse
            ]);
            
            Cookie::queue('flarum_remember', $token, $lifetime, '/forum');
            Log::info('Set Flarum cookie with token', [
                'token_preview' => substr($token, 0, 10) . '...',
                'lifetime' => $lifetime
            ]);
        } else {
            Log::error('Failed to set Flarum cookie - no token available', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        }
    }

    public function loginUser($token, Login $event)
    {
        $user = $event->user;
        $endpoint = config('forum.host') . '/login';
        
        Log::info('Attempting to login user to Flarum', [
            'user_id' => $user->id,
            'endpoint' => $endpoint,
            'token_preview' => substr($token, 0, 10) . '...'
        ]);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $token . '; userId=1'
            ])->post($endpoint, [
                'identification' => $user->email,
                'password' => $user->forum_password,
            ]);
            
            if (!$response->successful()) {
                Log::error('Flarum login failed', [
                    'endpoint' => $endpoint,
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'user_id' => $user->id
                ]);
            }
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception during Flarum login request', [
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return ['error' => $e->getMessage()];
        }
    }

    public function createUser(Login $event)
    {
        $user = $event->user;
        $randomPassword = Str::random(16);
        $user->forum_password = $randomPassword;
        $user->save();
        
        $endpoint = config('forum.host') . '/api/users';
        $payload = [
            'data' => [
                'type' => 'users',
                'attributes' => [
                    'username' => $user->username,
                    'email' => $user->email,
                    'password' => $randomPassword,
                    'isEmailConfirmed' => true,
                ]
            ]
        ];
        
        Log::info('Attempting to create Flarum user', [
            'endpoint' => $endpoint,
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email
        ]);
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Token ' . config('forum.api') . '; userId=1'
            ])->post($endpoint, $payload);
            
            if ($response->successful()) {
                Log::info('Flarum user created successfully', [
                    'endpoint' => $endpoint,
                    'status_code' => $response->status(),
                    'response_data' => $response->json()
                ]);
            } else {
                Log::error('Flarum user creation failed', [
                    'endpoint' => $endpoint,
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'request_payload' => $payload
                ]);
            }
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception during Flarum user creation', [
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_payload' => $payload
            ]);
            
            return ['error' => $e->getMessage()];
        }
    }

    public function getToken(Login $event)
    {
        $user = $event->user;
        $endpoint = config('forum.host') . '/api/token';
        $payload = [
            'identification' => $user->email,
            'password' => $user->forum_password,
            'lifetime' => 60 * 24 * 14,
            'remember' => 1
        ];
        
        Log::info('Attempting to get Flarum token', [
            'endpoint' => $endpoint,
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        
        try {
            $tokenResponse = Http::post($endpoint, $payload);
            
            if ($tokenResponse->successful()) {
                $responseData = $tokenResponse->json();
                $token = $responseData['token'] ?? null;
                
                if ($token) {
                    Log::info('Successfully retrieved Flarum token', [
                        'endpoint' => $endpoint,
                        'token_preview' => substr($token, 0, 10) . '...'
                    ]);
                } else {
                    Log::error('Flarum token response missing token field', [
                        'endpoint' => $endpoint,
                        'response_data' => $responseData
                    ]);
                }
                
                return $token;
            } else {
                Log::error('Failed to get Flarum token', [
                    'endpoint' => $endpoint,
                    'status_code' => $tokenResponse->status(),
                    'response_body' => $tokenResponse->body(),
                    'user_email' => $user->email
                ]);
                
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception during Flarum token request', [
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
}