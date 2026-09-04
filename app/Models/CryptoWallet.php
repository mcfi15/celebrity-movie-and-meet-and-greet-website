<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CryptoWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'wallet_address',
        'wallet_image',
        'qr_code_image',
        'instructions',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wallet) {
            if (empty($wallet->slug)) {
                $wallet->slug = Str::slug($wallet->name);
            }

            if (is_null($wallet->sort_order)) {
                $wallet->sort_order = static::max('sort_order') + 1;
            }
        });

        static::updating(function ($wallet) {
            if ($wallet->isDirty('name') && empty($wallet->slug)) {
                $wallet->slug = Str::slug($wallet->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getWalletImageUrlAttribute()
    {
        return $this->wallet_image ? asset('storage/' . $this->wallet_image) : null;
    }

    public function getQrCodeImageUrlAttribute()
    {
        return $this->qr_code_image ? asset('storage/' . $this->qr_code_image) : null;
    }
}
