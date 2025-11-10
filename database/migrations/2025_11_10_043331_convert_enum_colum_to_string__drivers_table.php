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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('category_backup')->after('category');
            DB::statement('UPDATE drivers SET category_backup = category');
            $table->dropColumn('category');
            $table->string('category')->after('category_backup');
            DB::statement('UPDATE drivers SET category = category_backup');
            $table->dropColumn('category_backup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
