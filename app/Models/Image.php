<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'title',
        'description',
        'imageable_type',
        'imageable_id',
        'type',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent imageable model (celebrity, testimonial, etc.).
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get only active images
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured images
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get images by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get the full URL of the image
     */
    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Get the full file path
     */
    public function getFullPathAttribute()
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    /**
     * Get human readable file size
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get image dimensions as string
     */
    public function getDimensionsAttribute()
    {
        if ($this->width && $this->height) {
            return $this->width . ' × ' . $this->height;
        }
        return null;
    }

    /**
     * Check if image exists on disk
     */
    public function exists()
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Delete the image file from storage
     */
    public function deleteFile()
    {
        if ($this->exists()) {
            return Storage::disk($this->disk)->delete($this->path);
        }
        return true;
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete file when model is deleted
        static::deleting(function ($image) {
            $image->deleteFile();
        });
    }

    /**
     * Get thumbnail URL (you can implement thumbnail generation logic here)
     */
    public function getThumbnailUrl($width = 150, $height = 150)
    {
        // For now, return the original image
        // You can integrate with image manipulation libraries like Intervention Image
        return $this->url;
    }

    /**
     * Check if the image is an actual image file
     */
    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get the file extension
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }
}
