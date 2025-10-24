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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('category')->default('general'); // general, room, bathroom, etc.
            $table->timestamps();
        });

        // Pivot table for accommodation amenities
        Schema::create('accommodation_amenity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['accommodation_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_amenity');
        Schema::dropIfExists('amenities');
    }
};
