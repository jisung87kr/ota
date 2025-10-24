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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable'); // polymorphic relation
            $table->string('path');
            $table->string('type')->default('gallery'); // main, gallery, thumbnail
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['imageable_type', 'imageable_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
