<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Design extends Model
{
    /** @use HasFactory<\Database\Factories\DesignFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'tag',
        'card_image',
        'active',
        'category',
        'price',
        'build_cost',
        'impedance',
        'power',
        'summary',
        'description',
        'bill_of_materials',
        'frd_files',
        'enclosure_files',
        'electronic_files',
        'design_other_files',
        'official'
    ];

    protected $casts = [
        'active' => 'boolean',
        'bill_of_materials' => 'array',
        'frd_files' => 'array',
        'enclosure_files' => 'array',
        'electronic_files' => 'array',
        'design_other_files' => 'array'
    ];

    public function designer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(DesignDriver::class);
    }

    public function sales(): hasMany
    {
        return $this->hasMany(DesignPurchase::class)
            ->with('user');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($design) {
            try {
                $apiKey = env('FLARUM_API_KEY');
                $userId = $design->user_id;
                $flarumUrl = rtrim(env('FLARUM_URL'), '/'); // Remove trailing slash if present

                Log::info('Attempting Flarum API call', [
                    'api_key' => substr($apiKey, 0, 5) . '...',
                    'user_id' => $userId,
                    'url' => $flarumUrl . '/api/discussions' // Log full URL for debugging
                ]);

                $response = Http::withHeaders([
                    'Authorization' => "Token {$apiKey}; userId={$userId}",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/vnd.api+json'
                ])->post($flarumUrl . '/api/discussions', [
                    'data' => [
                        'type' => 'discussions',
                        'attributes' => [
                            'title' => $design->name,
                            'content' => "New design posted: " . $design->description
                        ]
                    ]
                ]);

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
        });
    }
}
