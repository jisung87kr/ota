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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->after('email');
            $table->string('status')->default('active')->after('role');
            $table->string('phone')->nullable()->after('name');
            $table->text('business_info')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('business_info');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');

            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'role',
                'status',
                'phone',
                'business_info',
                'approved_at',
                'approved_by'
            ]);
        });
    }
};
