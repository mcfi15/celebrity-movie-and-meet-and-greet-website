<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FeatureSmokeTest extends TestCase
{
    private ?array $originalSettings = null;

    protected function setUp(): void
    {
        parent::setUp();

        $setting = SiteSetting::getSetting();
        $this->originalSettings = [
            'passcode_enabled' => $setting->passcode_enabled,
            'site_passcode' => $setting->site_passcode,
        ];
    }

    protected function tearDown(): void
    {
        SiteSetting::getSetting()->update([
            'passcode_enabled' => $this->originalSettings['passcode_enabled'],
            'site_passcode' => $this->originalSettings['site_passcode'],
        ]);

        parent::tearDown();
    }

    public function test_passcode_entry_page_renders(): void
    {
        $response = $this->get(route('passcode.entry'));
        $response->assertOk();
        $response->assertSee('Restricted Access');
    }

    public function test_wrong_passcode_rejected(): void
    {
        SiteSetting::getSetting()->update([
            'passcode_enabled' => true,
            'site_passcode' => bcrypt('secret1234'),
        ]);

        $response = $this->post(route('passcode.verify'), ['passcode' => 'wrong']);
        $response->assertSessionHasErrors('passcode');
    }

    public function test_correct_passcode_grants_access(): void
    {
        SiteSetting::getSetting()->update([
            'passcode_enabled' => true,
            'site_passcode' => bcrypt('secret1234'),
        ]);

        $response = $this->post(route('passcode.verify'), ['passcode' => 'secret1234']);
        $response->assertSessionHas('site_passcode_verified');
    }

    public function test_public_route_redirects_when_not_verified(): void
    {
        SiteSetting::getSetting()->update([
            'passcode_enabled' => true,
            'site_passcode' => bcrypt('secret1234'),
        ]);

        $this->get('/about')
            ->assertRedirect(route('passcode.entry'));
    }

    public function test_verified_session_can_browse(): void
    {
        SiteSetting::getSetting()->update([
            'passcode_enabled' => true,
            'site_passcode' => bcrypt('secret1234'),
        ]);

        $this->withSession(['site_passcode_verified' => true])
            ->get('/about')
            ->assertOk();
    }

    public function test_booking_create_page_renders(): void
    {
        $this->get(route('booking.create'))
            ->assertOk();
    }

    public function test_payment_section_respects_payment_enabled_setting(): void
    {
        $setting = SiteSetting::getSetting();
        $original = [
            'payment_enabled' => $setting->payment_enabled,
            'payment_methods' => $setting->payment_methods,
        ];

        // Payments disabled -> no payment method section, no "Pay Later" option
        $setting->update(['payment_enabled' => false]);
        $this->get(route('booking.create'))
            ->assertOk()
            ->assertDontSee('Pay Later (After Approval)')
            ->assertDontSee('id="payment_later"');

        // Payments enabled -> payment section (with "Pay Later") is shown again
        $setting->update(['payment_enabled' => true, 'payment_methods' => ['crypto']]);
        $this->get(route('booking.create'))
            ->assertOk()
            ->assertSee('Pay Later (After Approval)');

        // Restore original settings
        $setting->update([
            'payment_enabled' => $original['payment_enabled'],
            'payment_methods' => $original['payment_methods'],
        ]);
    }

    public function test_admin_crypto_wallets_index_renders(): void
    {
        $admin = User::where('role', 'admin')->first()
            ?? User::create([
                'name' => 'Smoke Admin',
                'email' => 'smoke_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]);

        $this->actingAs($admin)
            ->get(route('admin.crypto-wallets.index'))
            ->assertOk();
    }

    public function test_admin_can_create_crypto_wallet_with_uploads(): void
    {
        Storage::fake('public');

        $admin = User::where('role', 'admin')->first()
            ?? User::create([
                'name' => 'Smoke Admin',
                'email' => 'smoke_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]);

        $this->actingAs($admin)
            ->post(route('admin.crypto-wallets.store'), [
                'name' => 'Tether (USDT - TRC20)',
                'wallet_address' => 'TXn7oFw8mCzvJx9kLmQpZr1aBcDeFgHiJkLmNoPqR',
                'instructions' => 'Send only USDT via TRC20 network. Minimum deposit $50.',
                'is_active' => '1',
                'wallet_image' => UploadedFile::fake()->image('wallet.png'),
                'qr_code_image' => UploadedFile::fake()->image('qr.png'),
            ])
            ->assertRedirect(route('admin.crypto-wallets.index'));

        $this->assertDatabaseHas('crypto_wallets', [
            'name' => 'Tether (USDT - TRC20)',
        ]);

        $wallet = \App\Models\CryptoWallet::where('name', 'Tether (USDT - TRC20)')->latest('id')->first();
        Storage::disk('public')->assertExists($wallet->wallet_image);
        Storage::disk('public')->assertExists($wallet->qr_code_image);

        $wallet->forceDelete();
    }

    public function test_admin_settings_page_renders(): void
    {
        $admin = User::where('role', 'admin')->first()
            ?? User::create([
                'name' => 'Smoke Admin',
                'email' => 'smoke_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertOk();
    }

    public function test_crypto_booking_success_page_and_proof_submission(): void
    {
        Storage::fake('public');

        $wallet = \App\Models\CryptoWallet::create([
            'name' => 'Bitcoin (BTC - SegWit)',
            'slug' => 'btc-segwit-' . uniqid(),
            'wallet_address' => 'bc1qsmoke1234567890abcdefghijklmnopqrstuv',
            'instructions' => 'Send only BTC via the SegWit network.',
            'is_active' => true,
        ]);

        $celebrity = \App\Models\Celebrity::firstOrFail();
        $serviceType = \App\Models\ServiceType::firstOrFail();

        $booking = \App\Models\Booking::create([
            'booking_number' => 'BK-SMOKE-' . uniqid(),
            'celebrity_id' => $celebrity->id,
            'service_type_id' => $serviceType->id,
            'customer_name' => 'Smoke Customer',
            'customer_email' => 'smoke_customer@example.com',
            'customer_phone' => '+1-555-000-0000',
            'event_date' => now()->addMonths(2),
            'duration_hours' => 2,
            'base_price' => 500,
            'total_amount' => 1000,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'crypto',
            'crypto_wallet_id' => $wallet->id,
        ]);

        // Success page renders wallet details + copy button
        $this->get(route('booking.success', $booking->booking_number))
            ->assertOk()
            ->assertSee($wallet->wallet_address)
            ->assertSee('data-copy-address="' . $wallet->wallet_address . '"', false);

        // Submit proof
        $this->post(route('booking.payment.submit', $booking), [
            'customer_email' => 'smoke_customer@example.com',
            'payment_tx_hash' => '0xdeadbeef1234567890abcdef',
            'payment_proof_image' => UploadedFile::fake()->image('receipt.png'),
        ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('pending_payment_verification', $booking->status);
        $this->assertEquals('pending_verification', $booking->payment_status);
        $this->assertEquals('0xdeadbeef1234567890abcdef', $booking->payment_tx_hash);

        Storage::disk('public')->assertExists($booking->payment_proof_image);

        // Admin review page renders the verification panel
        $admin = User::where('role', 'admin')->first()
            ?? User::create([
                'name' => 'Smoke Admin',
                'email' => 'smoke_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]);

        $this->actingAs($admin)
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee($wallet->wallet_address);

        // Admin approves the payment
        $this->actingAs($admin)
            ->patch(route('admin.bookings.approve-payment', $booking))
            ->assertRedirect();

        $booking->refresh();
        $this->assertEquals('approved', $booking->status);
        $this->assertEquals('paid', $booking->payment_status);

        $booking->forceDelete();
        $wallet->forceDelete();
    }
}