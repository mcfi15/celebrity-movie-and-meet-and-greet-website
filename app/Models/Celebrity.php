<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Celebrity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'profession',
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
        return $this->services()->where('is_available', true)
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
}
