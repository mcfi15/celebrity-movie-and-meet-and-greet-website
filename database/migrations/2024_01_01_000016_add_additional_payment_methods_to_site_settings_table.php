<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('paypal_enabled')->default(false)->after('bank_transfer_enabled');
            $table->boolean('cash_enabled')->default(false)->after('paypal_enabled');
            $table->string('paypal_client_id')->nullable()->after('crypto_wallet_address');
            $table->string('paypal_client_secret')->nullable()->after('paypal_client_id');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['paypal_enabled', 'cash_enabled', 'paypal_client_id', 'paypal_client_secret']);
        });
    }
};
