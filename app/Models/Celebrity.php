<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Traits\HasImages;

class Celebrity extends Model
{
    use HasFactory, HasImages;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'profession',
        'category',
        'hourly_rate',
        'image',
        'gallery',
        'base_price',
        'is_active',
        'rating',
        'achievements',
        'social_media',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'social_media' => 'array',
        'base_price' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($celebrity) {
            if (!$celebrity->slug) {
                $celebrity->slug = Str::slug($celebrity->name);
            }
        });
    }

    public function services()
    {
        return $this->hasMany(CelebrityService::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function availableServices()
    {
        return $this->services()->where('is_active', true)
                   ->with('serviceType')
                   ->orderBy('price');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Automatically sync hourly_rate with base_price for backward compatibility
    public function setHourlyRateAttribute($value)
    {
        $this->attributes['hourly_rate'] = $value;
        $this->attributes['base_price'] = $value; // Keep both in sync
    }

    // If base_price is set directly, sync with hourly_rate
    public function setBasePriceAttribute($value)
    {
        $this->attributes['base_price'] = $value;
        $this->attributes['hourly_rate'] = $value; // Keep both in sync
    }
}
