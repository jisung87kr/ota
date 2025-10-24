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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            // Payment identifiers
            $table->string('merchant_uid')->unique(); // Our transaction ID
            $table->string('imp_uid')->nullable()->unique(); // PG transaction ID (PortOne)
            $table->string('pg_tid')->nullable(); // PG provider transaction ID

            // Payment details
            $table->string('payment_method'); // card, bank_transfer, etc.
            $table->decimal('amount', 10, 2); // Payment amount
            $table->string('currency', 3)->default('KRW');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'refunded', 'partially_refunded'])->default('pending');

            // Card information (if applicable) - only non-sensitive data
            $table->string('card_name')->nullable(); // Card company name
            $table->string('card_number')->nullable(); // Masked card number (e.g., 1234-****-****-5678)

            // Refund information
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();

            // Payment gateway response
            $table->text('pg_response')->nullable(); // Store full PG response as JSON
            $table->text('fail_reason')->nullable();

            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('merchant_uid');
            $table->index('imp_uid');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
