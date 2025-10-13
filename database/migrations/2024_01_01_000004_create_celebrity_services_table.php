<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('celebrity_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('celebrity_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['celebrity_id', 'service_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('celebrity_services');
    }
};