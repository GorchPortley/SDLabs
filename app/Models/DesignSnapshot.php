<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignSnapshot extends Model
{
    protected $fillable = [
        'design_id',
        'snapshot_name',
        'stashed_data',
        'stashed_paths',
        'download_path'
    ];

    protected $casts = [
        'stashed_data' => 'array',
        'stashed_paths' => 'array'
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
