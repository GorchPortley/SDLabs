<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use ZipArchive;
use Laravel\Scout\Searchable;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;
    use Searchable;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'active' => $this->active,
            'tag' => $this->tag
        ];
    }

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'tag',
        'active',
        'category',
        'size',
        'impedance',
        'power',
        'price',
        'link',
        'summary',
        'description',
        'factory_specs',
        'frequency_files',
        'impedance_files',
        'other_files',
        'card_image',
        'official',
        'forum_slug'
    ];
    protected $casts = [
        'active' => 'boolean',
        'factory_specs' => 'array',
        'frequency_files' => 'array',
        'impedance_files' => 'array',
        'other_files' => 'array',
        'card_image' => 'array'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function designs(): HasMany
    {
        return $this->hasMany(DesignDriver::class);
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(DriverSnapshot::class);
    }
    public function createDriverSnapshot(?Driver $driver, string $version)
    {
        $sourceDirectory = "files/{$driver->user_id}/Drivers/{$driver->model}/";
        $zipFileName = "{$driver->brand}-{$driver->model}-{$version}-SDLabs.zip";
        $zipFilePath = Storage::path($sourceDirectory . $zipFileName);

        try {
            if (!Storage::exists($sourceDirectory)) {
                Log::warning("Design snapshot failed: Directory not found", [
                    'driver_id' => $driver->id,
                    'directory' => $sourceDirectory
                ]);
                return false;
            }



            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                Log::error("Failed to create zip archive", [
                    'design_id' => $driver->id,
                    'path' => $zipFilePath
                ]);
                return false;
            }

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(Storage::path($sourceDirectory), RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                $filepath = $file->getRealPath();
                $relativePath = substr($filepath, strlen(Storage::path($sourceDirectory)));

                if ($filepath === $zipFilePath) continue;

                $file->isDir()
                    ? $zip->addEmptyDir($relativePath)
                    : $zip->addFile($filepath, $relativePath);
            }
            $zip->close();

            DriverSnapshot::create([
                'driver_id' => $driver->id,
                'snapshot_name' => $zipFileName,
                'stashed_data' => ["1"=>"1"],
                'download_path' => $sourceDirectory . $zipFileName,
            ]);


            return true;
        } catch (\Exception $e) {
            Log::error("Snapshot creation failed", [
                'driver_id' => $driver->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }

    }

    public function download(DriverSnapshot $snapshot)
    {


        $file= public_path(). "/storage/". $snapshot->download_path;


        return response()->download($file);}

}
