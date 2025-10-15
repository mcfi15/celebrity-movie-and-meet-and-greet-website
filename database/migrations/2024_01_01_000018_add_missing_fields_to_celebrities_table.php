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
        Schema::table('celebrities', function (Blueprint $table) {
            // Add category field
            $table->string('category')->nullable()->after('profession');
            
            // Add hourly_rate field (keep base_price for backward compatibility)
            $table->decimal('hourly_rate', 10, 2)->default(0)->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('celebrities', function (Blueprint $table) {
            $table->dropColumn(['category', 'hourly_rate']);
        });
    }
};
