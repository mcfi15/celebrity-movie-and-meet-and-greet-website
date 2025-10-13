<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of sliders
     */
    public function index()
    {
        $sliders = Slider::ordered()->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new slider
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'cta_text' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
            'order_position' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/slider/',$filename);
            $request->image = "uploads/slider/$filename";
        }

        // Handle image upload
        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('sliders', 'public');
        // }

        // Set order position if not provided
        $orderPosition = $request->order_position ?? (Slider::max('order_position') + 1);

        Slider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image_path' => $request->image,
            'cta_text' => $request->cta_text,
            'cta_link' => $request->cta_link,
            'order_position' => $orderPosition,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider created successfully!');
    }

    /**
     * Display the specified slider
     */
    public function show(Slider $slider)
    {
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified slider
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'cta_text' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
            'order_position' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Handle image upload if new image is provided
        $imagePath = $slider->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath && !filter_var($imagePath, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('sliders', 'public');
        }

        $slider->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image_path' => $imagePath,
            'cta_text' => $request->cta_text,
            'cta_link' => $request->cta_link,
            'order_position' => $request->order_position ?? $slider->order_position,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider updated successfully!');
    }

    /**
     * Remove the specified slider
     */
    public function destroy(Slider $slider)
    {
        $slider->delete(); // Image deletion is handled in the model's boot method
        
        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider deleted successfully!');
    }

    /**
     * Update the order of sliders via AJAX
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'slider_ids' => 'required|array',
            'slider_ids.*' => 'exists:sliders,id'
        ]);

        foreach ($request->slider_ids as $index => $sliderId) {
            Slider::where('id', $sliderId)->update(['order_position' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Slider order updated successfully!']);
    }

    /**
     * Toggle slider active status via AJAX
     */
    public function toggleStatus(Slider $slider)
    {
        $slider->update(['is_active' => !$slider->is_active]);
        
        $status = $slider->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true, 
            'message' => "Slider {$status} successfully!",
            'is_active' => $slider->is_active
        ]);
    }
}
