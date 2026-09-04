<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('crypto_wallet_id')
                ->nullable()
                ->after('payment_method')
                ->constrained('crypto_wallets')
                ->nullOnDelete();

            $table->string('payment_tx_hash')->nullable()->after('payment_reference');
            $table->string('payment_proof_image')->nullable()->after('payment_tx_hash');
            $table->text('payment_notes')->nullable()->after('payment_proof_image');
            $table->timestamp('payment_submitted_at')->nullable()->after('payment_notes');
            $table->timestamp('payment_reviewed_at')->nullable()->after('payment_submitted_at');
        });

        // Normalize any legacy/overflow values before tightening the enums.
        DB::statement("UPDATE `bookings` SET `payment_status` = 'pending' WHERE `payment_status` NOT IN ('pending','paid','failed','refunded')");
        DB::statement("UPDATE `bookings` SET `status` = 'pending' WHERE `status` NOT IN ('pending','approved','rejected','completed','cancelled')");

        // MySQL cannot switch enum values with the schema builder alone, so we
        // extend the existing enums via raw statements to add the new states.
        DB::statement("ALTER TABLE `bookings` MODIFY `status` ENUM('pending','approved','rejected','completed','cancelled','pending_payment_verification') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE `bookings` MODIFY `payment_status` ENUM('pending','paid','failed','refunded','pending_verification') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `bookings` MODIFY `status` ENUM('pending','approved','rejected','completed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE `bookings` MODIFY `payment_status` ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending'");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('crypto_wallet_id');
            $table->dropColumn([
                'payment_tx_hash',
                'payment_proof_image',
                'payment_notes',
                'payment_submitted_at',
                'payment_reviewed_at',
            ]);
        });
    }
};
