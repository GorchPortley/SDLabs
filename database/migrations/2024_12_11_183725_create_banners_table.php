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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Optional name for internal reference
            $table->string('image_path'); // Path to the uploaded image
            $table->string('location'); // Where the banner should be displayed
            $table->date('start_date')->nullable(); // Optional start date
            $table->date('end_date')->nullable(); // Optional end date
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // For ordering banners
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
