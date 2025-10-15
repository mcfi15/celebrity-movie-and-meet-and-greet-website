<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelebrityService extends Model
{
    use HasFactory;

    protected $fillable = [
        'celebrity_id',
        'service_type_id',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function celebrity()
    {
        return $this->belongsTo(Celebrity::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_type_id', 'service_type_id')
                   ->where('celebrity_id', $this->celebrity_id);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }
}