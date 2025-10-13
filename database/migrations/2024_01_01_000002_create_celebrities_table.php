<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('celebrities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->string('profession')->nullable(); // Actor, Singer, etc.
            $table->string('image')->nullable();
            $table->json('gallery')->nullable(); // Additional images
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('rating')->default(5);
            $table->text('achievements')->nullable();
            $table->json('social_media')->nullable(); // Instagram, Twitter, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('celebrities');
    }
};
