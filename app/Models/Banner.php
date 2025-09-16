<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Banner extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'location',
        'start_date',
        'end_date',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Scope a query to only include active banners for a specific location
     */
    public function scopeActiveForLocation($query, $location)
    {
        return $query->where('location', $location)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc');
    }
}
