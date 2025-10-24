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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('accommodation_id')->constrained()->onDelete('cascade');

            // Review content
            $table->string('title');
            $table->text('content');
            $table->decimal('rating', 2, 1); // Overall rating (1.0 to 5.0)

            // Category ratings (optional)
            $table->decimal('cleanliness_rating', 2, 1)->nullable();
            $table->decimal('service_rating', 2, 1)->nullable();
            $table->decimal('location_rating', 2, 1)->nullable();
            $table->decimal('value_rating', 2, 1)->nullable();

            // Photos (JSON array of file paths)
            $table->json('photos')->nullable();

            // Helpfulness counter
            $table->integer('helpful_count')->default(0);

            // Moderation
            $table->boolean('is_visible')->default(true);
            $table->timestamp('hidden_at')->nullable();
            $table->text('hidden_reason')->nullable();

            // Response from accommodation (optional)
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('accommodation_id');
            $table->index('user_id');
            $table->index('rating');
            $table->index('is_visible');
            $table->index('created_at');

            // One review per booking
            $table->unique('booking_id');
        });

        // Review helpfulness tracking (who found it helpful)
        Schema::create('review_helpfulness', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // One vote per user per review
            $table->unique(['review_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_helpfulness');
        Schema::dropIfExists('reviews');
    }
};
