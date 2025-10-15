<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $testimonials = Testimonial::orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $testimonial = new Testimonial($request->all());

        // Handle image upload
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $imageName = time() . '_' . $image->getClientOriginalName();
        //     $imagePath = $image->storeAs('testimonials', $imageName, 'public');
        //     $testimonial->image = $imagePath;
        // }
        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/testimonials/',$filename);
            $testimonial->image = "uploads/testimonials/$filename";
        }

        $testimonial->save();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial has been created successfully.');
    }

    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $testimonial->fill($request->all());

        if($request->hasFile('image')){
 
            $path = $testimonial->image;
            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/testimonials/',$filename);
            $$testimonial->image = "uploads/testimonials/$filename";
        }

        $testimonial->save();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial has been updated successfully.');
    }

    public function destroy(Testimonial $testimonial){
        if($testimonial->count() > 0){
            $destination = $testimonial->image;
            if(File::exists($destination)){
                File::delete($destination);
            }
            $testimonial->delete();
            return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial has been deleted successfully.');
            
        }
        return redirect()-back()->with('message', 'Something went wrong'); 
    }

    public function toggleFeatured(Testimonial $testimonial)
    {
        $testimonial->is_featured = !$testimonial->is_featured;
        $testimonial->save();

        $status = $testimonial->is_featured ? 'featured' : 'unfeatured';
        
        return response()->json([
            'success' => true,
            'message' => "Testimonial has been {$status} successfully.",
            'is_featured' => $testimonial->is_featured
        ]);
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();

        $status = $testimonial->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Testimonial has been {$status} successfully.",
            'is_active' => $testimonial->is_active
        ]);
    }
}
