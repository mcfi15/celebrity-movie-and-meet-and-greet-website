<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle', 
        'description',
        'image_path',
        'cta_text',
        'cta_link',
        'order_position',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_position' => 'integer'
    ];

    /**
     * Scope to get only active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get sliders ordered by position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_position');
    }

    /**
     * Get the full URL for the slider image
     */
    public function getImageUrlAttribute()
{
    if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
        return $this->image_path;
    }
    
    return Storage::disk('public')->url($this->image_path);
}

    /**
     * Check if the slider has a call-to-action button
     */
    public function hasCta()
    {
        return !empty($this->cta_text) && !empty($this->cta_link);
    }

    /**
     * Get formatted CTA link (ensures it starts with http)
     */
    public function getFormattedCtaLinkAttribute()
    {
        if (empty($this->cta_link)) {
            return null;
        }

        // If it starts with /, it's an internal link
        if (str_starts_with($this->cta_link, '/')) {
            return url($this->cta_link);
        }

        // If it doesn't start with http, add https://
        if (!str_starts_with($this->cta_link, 'http')) {
            return 'https://' . $this->cta_link;
        }

        return $this->cta_link;
    }

    /**
     * Delete slider image when slider is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($slider) {
            if ($slider->image_path && !filter_var($slider->image_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($slider->image_path);
            }
        });
    }
}

