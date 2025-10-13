<?php

namespace App\Models\Traits;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

trait HasImages
{
    /**
     * Get all images for this model
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->ordered();
    }

    /**
     * Get the main image
     */
    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')
                   ->where('type', 'main')
                   ->where('is_active', true);
    }

    /**
     * Get gallery images
     */
    public function galleryImages()
    {
        return $this->morphMany(Image::class, 'imageable')
                   ->where('type', 'gallery')
                   ->where('is_active', true)
                   ->ordered();
    }

    /**
     * Get featured images
     */
    public function featuredImages()
    {
        return $this->morphMany(Image::class, 'imageable')
                   ->where('is_featured', true)
                   ->where('is_active', true)
                   ->ordered();
    }

    /**
     * Add an image to this model
     */
    public function addImage(
        UploadedFile $file, 
        string $type = 'main', 
        array $attributes = []
    ): Image {
        $directory = $this->getImageDirectory();
        
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs($directory, $filename, 'public');
        
        // Get image dimensions
        $dimensions = $this->getImageDimensions($file);
        
        // Create image record
        $imageData = array_merge([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions['width'] ?? null,
            'height' => $dimensions['height'] ?? null,
            'type' => $type,
            'is_active' => true,
        ], $attributes);
        
        return $this->images()->create($imageData);
    }

    /**
     * Add multiple images
     */
    public function addImages(array $files, string $type = 'gallery', array $attributes = []): array
    {
        $images = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $images[] = $this->addImage($file, $type, $attributes);
            }
        }
        
        return $images;
    }

    /**
     * Update the main image
     */
    public function updateMainImage(UploadedFile $file, array $attributes = []): Image
    {
        // Remove existing main image
        $this->images()->where('type', 'main')->delete();
        
        return $this->addImage($file, 'main', $attributes);
    }

    /**
     * Get the main image URL
     */
    public function getMainImageUrlAttribute(): ?string
    {
        $mainImage = $this->mainImage;
        return $mainImage ? $mainImage->url : null;
    }

    /**
     * Remove an image
     */
    public function removeImage(int $imageId): bool
    {
        $image = $this->images()->find($imageId);
        
        if ($image) {
            return $image->delete();
        }
        
        return false;
    }

    /**
     * Remove all images
     */
    public function removeAllImages(): bool
    {
        return $this->images()->delete();
    }

    /**
     * Get the directory for storing images
     */
    protected function getImageDirectory(): string
    {
        $modelName = strtolower(class_basename($this));
        return "images/{$modelName}/" . date('Y/m');
    }

    /**
     * Get image dimensions
     */
    protected function getImageDimensions(UploadedFile $file): array
    {
        try {
            if (function_exists('getimagesize')) {
                $dimensions = getimagesize($file->getPathname());
                return [
                    'width' => $dimensions[0] ?? null,
                    'height' => $dimensions[1] ?? null,
                ];
            }
        } catch (\Exception $e) {
            // If we can't get dimensions, continue without them
        }
        
        return [];
    }

    /**
     * Resize and optimize image (requires Intervention Image)
     */
    public function addOptimizedImage(
        UploadedFile $file, 
        string $type = 'main', 
        int $maxWidth = 1200, 
        int $maxHeight = 800,
        array $attributes = []
    ): Image {
        $directory = $this->getImageDirectory();
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Process image with Intervention Image if available
        if (class_exists('\Intervention\Image\Facades\Image')) {
            $processedImage = InterventionImage::make($file->getPathname())
                ->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Don't upsize smaller images
                })
                ->orientate(); // Fix orientation based on EXIF data
            
            // Store processed image
            $fullPath = storage_path('app/public/' . $directory . '/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }
            
            $processedImage->save($fullPath, 85); // 85% quality
            
            $path = $directory . '/' . $filename;
            
            // Get processed image info
            $size = filesize($fullPath);
            $width = $processedImage->width();
            $height = $processedImage->height();
        } else {
            // Fallback to regular upload if Intervention Image is not available
            $path = $file->storeAs($directory, $filename, 'public');
            $size = $file->getSize();
            $dimensions = $this->getImageDimensions($file);
            $width = $dimensions['width'] ?? null;
            $height = $dimensions['height'] ?? null;
        }
        
        // Create image record
        $imageData = array_merge([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'type' => $type,
            'is_active' => true,
        ], $attributes);
        
        return $this->images()->create($imageData);
    }
}
