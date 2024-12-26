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
        if (!Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('name');
                $table->string('genre');
                $table->text('description')->nullable();
                $table->dateTime('time_start');
                $table->dateTime('time_ends');
                $table->decimal('price', 8, 2);
                $table->unsignedBigInteger('studios_id');
                $table->unsignedBigInteger('instructors_id');
    
                // Add foreign keys if needed
                $table->foreign('studios_id')->references('id')->on('studios')->onDelete('cascade');
                $table->foreign('instructors_id')->references('id')->on('instructors')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
