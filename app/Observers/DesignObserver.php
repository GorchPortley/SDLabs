<?php

namespace App\Observers;

use App\Models\Design;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DesignObserver
{
    /**
     * Handle the Design "created" event.
     */
    public function created(Design $design): void
    {
        $this->createForumDiscussion($design);
    }

    /**
     * Create a forum discussion for the design
     */
    private function createForumDiscussion(Design $design): void
    {
        try {
            $forumEmail = auth()->user()->email;
            $forumPassword = auth()->user()->forum_password;

            // First, get the authentication token
            $tokenResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post(env('APP_URL') . '/forum/api/token', [
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
            ])->post(env('APP_URL') . '/forum/api/discussions', [
                'data' => [
                    'type' => 'discussions',
                    'attributes' => [
                        'title' => $design->name,
                        'content' => "New design posted: " . $design->summary . "View more at: " . env('APP_URL') . "/designs/design/" . $design->id
                    ],
                    'relationships' => [
                        'tags' => [
                            'data' => [
                                [
                                    'type' => 'tags',
                                    'id' => '2'
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
                    $design->forum_slug = $slug;
                    $design->save();
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
                'design_id' => $design->id
            ]);
        }
    }
}