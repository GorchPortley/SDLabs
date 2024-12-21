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

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

//Begin Fillable Area
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
        'official'
    ];
    protected $casts = [
        'active' => 'boolean',
        'factory_specs' => 'array',
        'frequency_files' => 'array',
        'impedance_files' => 'array',
        'other_files' => 'array',
    ];
//End Fillable Area

//Begin Relationships Area
    private function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    private function designs(): HasMany
    {
        return $this->hasMany(DesignDriver::class);
    }

    private function snapshots(): HasMany
    {
        return $this->hasMany(DriverSnapshot::class);
    }
//End Relationships Area

//Begin Snapshots Area

    //Helper
    private function getSourceDirectory(Driver $driver): string
    {
        return "files/{$driver->user_id}/Drivers/{$driver->model}/";
    }

    //Helper
    private function getZipFileName(Driver $driver, string $version): string
    {
        return "{$driver->brand}-{$driver->model}-{$version}-SDLabs.zip";
    }

    //Helper    
    private function getZipFilePath(Driver $driver, string $version): string
    {
        return $this->getSourceDirectory($driver) . $this->getZipFileName($driver, $version);
    }

    //Helper
    private function createZipArchive(string $sourceDirectory, string $zipFilePath): bool
    { 
       $zip = new ZipArchive();
    
        if ($zip->open(Storage::path($zipFilePath), ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error("Cannot open <$zipFilePath>");
            return false;
        }

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(Storage::path($sourceDirectory), RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
    
            foreach ($files as $file) {
                $filepath = $file->getRealPath();
                $relativePath = substr($filepath, strlen(Storage::path($sourceDirectory)));
    
                // Skip the zip file itself
                if ($filepath === $zipFilePath) continue;
    
                if ($file->isDir()) {
                    $zip->addEmptyDir($relativePath);
                } else {
                    $zip->addFile($filepath, $relativePath);
                }
            }
            $zip->close();
            return true;
    }

    //Helper
    private function submitSnapshotData(Driver $driver, string $sourceDirectory, string $zipFileName)
    {
        DriverSnapshot::create([
            'driver_id' => $driver->id,
            'snapshot_name' => $zipFileName,
            'stashed_data' => ["1"=>"1"],
            'download_path' => $sourceDirectory . $zipFileName,
        ]);
    }

    //Helper
    public function download(DriverSnapshot $snapshot)
    {


        $file= private_path(). "/storage/". $snapshot->download_path;


        return response()->download($file);
    }

    //Gets called in Dashboard->Driver->Create New Snapshot
    public function createDriverSnapshot(?Driver $driver, string $version)
    {
        $sourceDirectory = $this->getSourceDirectory($driver);
        $zipFileName = $this->getZipFileName($driver, $version);
        $zipFilePath = $this->getZipFilePath($driver, $version);
        $this->submitSnapshotData($driver, $sourceDirectory, $zipFileName);
        $this->createZipArchive($sourceDirectory, $zipFilePath);

    }
//End Snapshots Area

}
