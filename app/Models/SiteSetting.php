<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_email',
        'site_phone',
        'site_address',
        'site_logo',
        'site_favicon',
        'site_description',
        'about_content',
        'payment_enabled',
        'payment_methods',
        'stripe_enabled',
        'crypto_enabled',
        'bank_transfer_enabled',
        'paypal_enabled',
        'cash_enabled',
        'stripe_public_key',
        'stripe_secret_key',
        'crypto_wallet_address',
        'paypal_client_id',
        'paypal_client_secret',
        'theme_color',
        'site_passcode',
        'passcode_enabled',
    ];

    protected $casts = [
        'payment_enabled' => 'boolean',
        'stripe_enabled' => 'boolean',
        'crypto_enabled' => 'boolean',
        'bank_transfer_enabled' => 'boolean',
        'paypal_enabled' => 'boolean',
        'cash_enabled' => 'boolean',
        'passcode_enabled' => 'boolean',
        'payment_methods' => 'array',
    ];

    public static function getSetting()
    {
        return self::first() ?? self::create([
            'site_name' => 'Celebrity Movie Agency',
            'site_email' => 'info@celebrityagency.com',
            'site_phone' => '+1-555-123-4567',
            'site_address' => '123 Hollywood Blvd, Los Angeles, CA 90028',
            'site_description' => 'Premier celebrity booking agency for all your entertainment needs',
            'payment_enabled' => true,
            'stripe_enabled' => true,
            'crypto_enabled' => true,
            'payment_methods' => ['stripe', 'crypto'],
            'theme_color' => 'dark-gold',
        ]);
    }
}