<?php

namespace App\Observers;

use App\Models\Driver;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DriverObserver
{
    /**
     * Handle the Driver "created" event.
     */
    public function created(Driver $driver): void
    {
            try {
                $forumEmail = auth()->user()->email;
                $forumPassword = auth()->user()->forum_password;
    
                // First, get the authentication token
                $tokenResponse = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->post(config('forum.root') . '/forum/api/token', [
                    'identification' => $forumEmail,
                    'password' => $forumPassword
                ]);
    
                if (!$tokenResponse->successful()) {
                    Log::error('Failed to obtain Flarum token', [
                        'status' => $tokenResponse->status(),
                        'body' => $tokenResponse->body()
                    ]);
                    return;
                }
    
                $token = $tokenResponse->json()['token'];
    
                // Now create the discussion using the obtained token
                $response = Http::withHeaders([
                    'Authorization' => "Token {$token}",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/vnd.api+json'
                ])->post(config('forum.root') . '/forum/api/discussions', [
                    'data' => [
                        'type' => 'discussions',
                        'attributes' => [
                            'title' => $driver->model,
                            'content' => "New Driver posted: " . $driver->model . "View more at: " . config('forum.root') . "/drivers/driver/" . $driver->id
                        ],
                        'relationships' => [
                            'tags' => [
                                'data' => [
                                    [
                                        'type' => 'tags',
                                        'id' => '3'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
    
                if ($response->successful()) {
                    $responseData = $response->json();
                    $slug = $responseData['data']['attributes']['slug'] ?? null;
    
                    if ($slug) {
                        $driver->forum_slug = $slug;
                        $driver->save();
                    }
                }
    
                Log::info('Flarum API Response:', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
    
            } catch (\Exception $e) {
                Log::error('Failed to create Flarum discussion:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'design_id' => $driver->id
                ]);
            }
    }

    /**
     * Handle the Driver "updated" event.
     */
    public function updated(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "deleted" event.
     */
    public function deleted(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "restored" event.
     */
    public function restored(Driver $driver): void
    {
        //
    }

    /**
     * Handle the Driver "force deleted" event.
     */
    public function forceDeleted(Driver $driver): void
    {
        //
    }
}
