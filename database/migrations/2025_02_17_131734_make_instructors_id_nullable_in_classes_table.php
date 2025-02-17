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
        Schema::table('classes', function (Blueprint $table) {
            Schema::table('classes', function (Blueprint $table) {
                $table->foreignId('instructors_id')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            Schema::table('classes', function (Blueprint $table) {
                $table->foreignId('instructors_id')->nullable(false)->change();
            });
        });
    }
};
