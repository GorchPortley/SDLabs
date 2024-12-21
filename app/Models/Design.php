<?php

namespace App\Models;

use App\Models\DesignSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use ZipArchive;
use App\Models\DesignDriver;
use Illuminate\Support\Facades\Auth;

class Design extends Model
{
    /** @use HasFactory<\Database\Factories\DesignFactory> */
    use HasFactory;

//Begin Fillable Area
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
        'official',
        'forum_slug'
    ];

    protected $casts = [
        'active' => 'boolean',
        'bill_of_materials' => 'array',
        'frd_files' => 'array',
        'enclosure_files' => 'array',
        'electronic_files' => 'array',
        'design_other_files' => 'array',
        'card_image' => 'array',
    ];
//End Fillable Area

//Begin Relationships Area
    public function snapshots(): HasMany
    {
        return $this->hasMany(DesignSnapshot::class);
    }
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
//End Relationships Area

//Begin Flarum Discussion Generation

    //Helper
    private function getFlarumToken(): ?string
    {
        $flarumURL = env('FORUM_URL');
        $forumEmail = Auth::user()->email;
        $forumPassword = Auth::user()->getAuthPassword();
        $tokenResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($flarumURL . '/api/token', [
            'identification' => $forumEmail,
            'password' => $forumPassword
        ]);

        if (!$tokenResponse->successful()) {
            Log::error('Failed to obtain Flarum token', [
                'status' => $tokenResponse->status(),
                'body' => $tokenResponse->body()
            ]);
            return null;
        }
        return $tokenResponse->json()['token'];
    }

    //Helper
    private function createFlarumDiscussion(Design $design, string $token): void
    {
        $flarumURL = env('FORUM_URL');
        $response = Http::withHeaders([
            'Authorization' => "Token {$token}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/vnd.api+json'
        ])->post($flarumURL . '/api/discussions', [
            'data' => [
                'type' => 'discussions',
                'attributes' => [
                    'title' => $design->name,
                    'content' => "New design posted: " . $design->summary . "View more at: " . env('APP_URL') . "/designs/design/" . $design->id],
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
    }

    //Design Creation Event
    protected static function boot()
    {
        parent::boot();

        static::created(function ($design) {
            try {
                $token = $this->getFlarumToken();

                if (!$token) {
                    return;
                }

                $slug = $this->createFlarumDiscussion($design, $token);

                if ($slug) {
                    $design->forum_slug = $slug;
                    $design->save();
                }
            } catch (\Exception $e) {
                Log::error('Failed to create Flarum discussion:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'design_id' => $design->id
                ]);
            }
        });
    }
//End Flarum Discussion Generation


//Begin Snapshots Area

    //Helper
    private function getSourceDirectory(Design $design): string
    {
        return "files/{$design->user_id}/{$design->name}/";
    }

    //Helper
    private function getZipFileName(Design $design, string $version): string
    {
        return "{$design->name}-{$version}-SDLabs.zip";
    }

    //Helper
    private function getZipFilePath(Design $design, string $version): string
    {
        return $this->getSourceDirectory($design) . $this->getZipFileName($design, $version);
    }

    //Helper    
    private function generatePDF(Design $design, string $version, string $sourceDirectory)
    {
        $pdf = Pdf::setOptions(['isRemoteEnabled'=>true])->loadView('pdf.Design', ['variation'=>$version, 'design'=>$design]);
        $pdf->save(Storage::path($sourceDirectory . "{$design->name}-{$version}.pdf"));
    }

    //Helper
    private function createZipArchive(string $sourceDirectory, string $zipFilePath): bool
    {
        $zip = new ZipArchive();
    
        if ($zip->open(Storage::path($zipFilePath), ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error("Cannot open <$zipFilePath>");
            return false;
        }
    
        try {
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
        } catch (\Exception $e) {
            Log::error("Error while creating zip archive", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        } finally {
            $zip->close();
        }
    
        return true;
    }

    //Helper
    private function submitSnapshotData(Design $design, string $version, string $sourceDirectory, string $zipFilePath, string $zipFileName, )
    {
    
        $stashed_data = [
            "name" => $design->name,
            "designer" => $design->designer()->pluck('name'),
            "version" => $version,
            "images" => $design->card_image,
            "category" => $design->category,
            "build_cost" => $design->build_cost,
            "impedance" => $design->impedance,
            "power" => $design->power,
            "summary" => $design->summary,
            "description" => $design->description,
            "bom" => $design->bill_of_materials,
            "forum_link" => "https://www.sdlabs.cc/forum/$design->forum_slug",
            "components" => $design->components()->get()->map(function ($component) {
                return [
                    'position' => $component->position,
                    'quantity' => $component->quantity,
                    'low_frequency' => $component->low_frequency,
                    'high_frequency' => $component->high_frequency,
                    'air_volume' => $component->air_volume,
                    'description' => $component->description,
                    'specifications' => $component->specifications
                ];
            })->toArray()
        ];

        DesignSnapshot::create([
            'design_id' => $design->id,
            'snapshot_name' => $zipFileName,
            'stashed_data' => $stashed_data,
            'stashed_paths' => ["1"=>"1"],
            'download_path' => $sourceDirectory . $zipFileName,
        ]);

        return true;

    }

    //Helper
    public function download(DesignSnapshot $snapshot)
    {


        $file= public_path(). "/storage/". $snapshot->download_path;


        return response()->download($file);
    }

    //Gets called in Dashboard->Design->Create New Snapshot
    public function createDesignSnapshot(?Design $design, string $version)
    {
        $sourceDirectory = $this->getSourceDirectory($design);
        $zipFileName = $this->getZipFileName($design, $version);
        $zipFilePath = $this->getZipFilePath($design, $version);
        $this->generatePDF($design, $version, $sourceDirectory);
        $this->submitSnapshotData($design, $version, $sourceDirectory, $zipFilePath, $zipFileName);
        $this->createZipArchive($sourceDirectory, $zipFilePath, $design);
    }
//End Snapshots Area


}
