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
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Str;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use ZipArchive;
use App\Models\DesignDriver;

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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($design) {
            try {
                $flarumUrl = env('FORUM_URL');
                $forumUsername = auth()->user()->name;
                $forumPassword = auth()->user()->getAuthPassword();

                // First, get the authentication token
                $tokenResponse = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->post($flarumUrl . '/api/token', [
                    'identification' => $forumUsername,
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
                ])->post($flarumUrl . '/api/discussions', [
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
        });
    }

    public function createDesignSnapshot(?Design $design, string $version)
    {
        $sourceDirectory = "files/{$design->user_id}/{$design->name}/";
        $zipFileName = "{$design->name}-{$version}-SDLabs.zip";
        $zipFilePath = Storage::path($sourceDirectory . $zipFileName);

        Pdf::view('pdf.design', ['variation'=>$version,'design' => $design])
            ->save(Storage::path($sourceDirectory . "{$design->name}-{$version}.pdf"));
        try {
            if (!Storage::exists($sourceDirectory)) {
                Log::warning("Design snapshot failed: Directory not found", [
                    'design_id' => $design->id,
                    'directory' => $sourceDirectory
                ]);
                return false;
            }



            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                Log::error("Failed to create zip archive", [
                    'design_id' => $design->id,
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
        } catch (\Exception $e) {
            Log::error("Snapshot creation failed", [
                'design_id' => $design->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }

    }

    public function download(DesignSnapshot $snapshot)
    {


        $file= public_path(). "/storage/". $snapshot->download_path;


        return response()->download($file);}
}
