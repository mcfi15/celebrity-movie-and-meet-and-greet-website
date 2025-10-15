<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Font Awesome class or image URL
            $table->string('color')->default('#007bff'); // Hex color code
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Fee structure
            $table->decimal('processing_fee_percentage', 5, 2)->default(0); // e.g., 2.9 for 2.9%
            $table->decimal('processing_fee_fixed', 8, 2)->default(0); // e.g., 0.30 for $0.30
            
            // Amount limits
            $table->decimal('minimum_amount', 10, 2)->nullable();
            $table->decimal('maximum_amount', 10, 2)->nullable();
            
            // Instructions for customers
            $table->text('instructions')->nullable();
            
            // API Configuration (encrypted in real app)
            $table->string('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('webhook_url')->nullable();
            
            // Additional settings (JSON)
            $table->json('settings')->nullable();
            
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
