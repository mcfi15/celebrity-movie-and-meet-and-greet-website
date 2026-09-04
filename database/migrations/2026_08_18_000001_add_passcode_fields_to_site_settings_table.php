<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('site_passcode')->nullable()->after('crypto_wallet_address');
            $table->boolean('passcode_enabled')->default(false)->after('site_passcode');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['site_passcode', 'passcode_enabled']);
        });
    }
};
