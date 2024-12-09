<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('design_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->constrained('designs');
            $table->string('snapshot_name');
            $table->json('stashed_data');
            $table->json('stashed_paths');
            $table->string('download_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_snapshots');
    }
};
