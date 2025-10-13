<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    /**
     * Display a listing of images
     */
    public function index(Request $request)
    {
        $query = Image::query()->with('imageable');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('imageable_type', $request->model_type);
        }

        // Search by filename or original name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $images = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new image
     */
    public function create()
    {
        return view('admin.images.create');
    }

    /**
     * Store a newly created image
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string|max:50',
        ]);

        $file = $request->file('image');
        $directory = 'images/uploads/' . date('Y/m');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs($directory, $filename, 'public');
        
        // Get image dimensions
        $dimensions = getimagesize($file->getPathname());
        
        // Create image record
        $image = Image::create([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'alt_text' => $request->alt_text,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return redirect()->route('admin.images.index')
                        ->with('success', 'Image uploaded successfully.');
    }

    /**
     * Display the specified image
     */
    public function show(Image $image)
    {
        return view('admin.images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified image
     */
    public function edit(Image $image)
    {
        return view('admin.images.edit', compact('image'));
    }

    /**
     * Update the specified image
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string|max:50',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $image->update([
            'alt_text' => $request->alt_text,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.images.index')
                        ->with('success', 'Image updated successfully.');
    }

    /**
     * Remove the specified image
     */
    public function destroy(Image $image)
    {
        // Delete the file from storage
        $image->deleteFile();
        
        // Delete the database record
        $image->delete();

        return redirect()->route('admin.images.index')
                        ->with('success', 'Image deleted successfully.');
    }

    /**
     * Upload multiple images via AJAX
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'type' => 'required|string|max:50',
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {
            $directory = 'images/uploads/' . date('Y/m');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store the file
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Get image dimensions
            $dimensions = getimagesize($file->getPathname());
            
            // Create image record
            $image = Image::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $dimensions[0] ?? null,
                'height' => $dimensions[1] ?? null,
                'type' => $request->type,
                'is_active' => true,
            ]);

            $uploadedImages[] = [
                'id' => $image->id,
                'filename' => $image->filename,
                'original_name' => $image->original_name,
                'url' => $image->url,
                'size' => $image->formatted_size,
                'dimensions' => $image->dimensions,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedImages) . ' images uploaded successfully.',
            'images' => $uploadedImages,
        ]);
    }

    /**
     * Delete image via AJAX
     */
    public function destroyAjax(Image $image): JsonResponse
    {
        try {
            $image->deleteFile();
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image.',
            ], 500);
        }
    }

    /**
     * Update image order via AJAX
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:images,id',
            'images.*.sort_order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->images as $imageData) {
                Image::where('id', $imageData['id'])
                     ->update(['sort_order' => $imageData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image order updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image order.',
            ], 500);
        }
    }

    /**
     * Get images for a specific model (used in AJAX calls)
     */
    public function getForModel(Request $request): JsonResponse
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'type' => 'nullable|string',
        ]);

        $query = Image::where('imageable_type', $request->model_type)
                     ->where('imageable_id', $request->model_id)
                     ->where('is_active', true)
                     ->ordered();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $images = $query->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'filename' => $image->filename,
                'original_name' => $image->original_name,
                'url' => $image->url,
                'alt_text' => $image->alt_text,
                'title' => $image->title,
                'type' => $image->type,
                'size' => $image->formatted_size,
                'dimensions' => $image->dimensions,
                'is_featured' => $image->is_featured,
            ];
        });

        return response()->json([
            'success' => true,
            'images' => $images,
        ]);
    }
}
