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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_occupancy');
            $table->integer('size')->nullable(); // in square meters
            $table->string('bed_type')->nullable(); // single, double, queen, king
            $table->integer('bed_count')->default(1);
            $table->decimal('base_price', 10, 2);
            $table->integer('total_rooms'); // total inventory
            $table->string('main_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['accommodation_id', 'is_active']);
            $table->index('base_price');
        });

        // Pivot table for room amenities
        Schema::create('room_amenity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['room_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_amenity');
        Schema::dropIfExists('rooms');
    }
};
