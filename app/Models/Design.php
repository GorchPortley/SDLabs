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
                $response = Http::withHeaders([
                    'Authorization' => 'Token ' . env('FLARUM_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://sandbox.sdlabs.cc/forum/api/discussions', [
                    'data' => [
                        'type' => 'discussions',
                        'attributes' => [
                            'title' => $design->name, // changed from title to name to match your fillable
                            'content' => "New design posted: " . $design->description,
                        ]
                    ]
                ]);

                if (!$response->successful()) {
                    Log::error('Flarum API Error:', [
                        'status' => $response->status(),
                        'response' => $response->json(),
                        'url' => env('FLARUM_URL') . '/api/discussions' // log the URL for debugging
                    ]);
                } else {
                    Log::info('Successfully created Flarum discussion', [
                        'design_id' => $design->id,
                        'discussion_id' => $response->json()['data']['id'] ?? null
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Failed to create Flarum discussion:', [
                    'error' => $e->getMessage(),
                    'design_id' => $design->id
                ]);
            }
        });
    }
}
