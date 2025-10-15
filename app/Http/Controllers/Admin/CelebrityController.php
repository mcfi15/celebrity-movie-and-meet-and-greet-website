<?php

namespace App\Http\Controllers\Admin;

use App\Models\Celebrity;
use App\Models\ServiceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CelebrityService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CelebrityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $celebrities = Celebrity::withCount(['bookings', 'services'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.celebrities.index', compact('celebrities'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', true)->orderBy('name')->get();
        return view('admin.celebrities.create', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'service_types' => 'array',
            'service_types.*' => 'exists:service_types,id',
        ]);

        $celebrity = new Celebrity($request->except(['image', 'service_types']));
        $celebrity->is_active = $request->has('is_active');
        
        // Handle main image upload
        // if ($request->hasFile('image')) {
        //     $imagePath = $this->uploadImage($request->file('image'), 'celebrities');
        //     $celebrity->image = $imagePath;
        // }

        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/celebrities/',$filename);
            $celebrity->image = "uploads/celebrities/$filename";
        }

        $celebrity->save();

        // Sync service types
        if ($request->has('service_types')) {
            foreach ($request->service_types as $serviceTypeId) {
                CelebrityService::create([
                    'celebrity_id' => $celebrity->id,
                    'service_type_id' => $serviceTypeId,
                ]);
            }
        }

        return redirect()->route('admin.celebrities.index')
            ->with('success', 'Celebrity has been created successfully.');
    }

    public function show(Celebrity $celebrity)
    {
        $celebrity->load(['services.serviceType', 'bookings']);
        return view('admin.celebrities.show', compact('celebrity'));
    }

    public function edit(Celebrity $celebrity)
    {
        $serviceTypes = ServiceType::where('is_active', true)->orderBy('name')->get();
        return view('admin.celebrities.edit', compact('celebrity', 'serviceTypes'));
    }

    public function update(Request $request, Celebrity $celebrity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'service_types' => 'array',
            'service_types.*' => 'exists:service_types,id',
        ]);

        $celebrity->fill($request->except(['image', 'service_types']));
        $celebrity->is_active = $request->has('is_active');

        if($request->hasFile('image')){
 
            $path = $celebrity->image;
            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/celebrities/',$filename);
            $celebrity->image = "uploads/celebrities/$filename";
        }

        
        // Handle main image upload
        // if ($request->hasFile('image')) {
        //     // Delete old image
        //     if ($celebrity->image) {
        //         Storage::disk('public')->delete($celebrity->image);
        //     }
        //     $celebrity->image = $this->uploadImage($request->file('image'), 'celebrities');
        // }

        $celebrity->save();

        // Update service types
        CelebrityService::where('celebrity_id', $celebrity->id)->delete();
        if ($request->has('service_types')) {
            foreach ($request->service_types as $serviceTypeId) {
                CelebrityService::create([
                    'celebrity_id' => $celebrity->id,
                    'service_type_id' => $serviceTypeId,
                ]);
            }
        }

        return redirect()->route('admin.celebrities.index')
            ->with('success', 'Celebrity has been updated successfully.');
    }

    public function destroy(Celebrity $celebrity){
        if($celebrity->count() > 0){
            $destination = $celebrity->image;
            if(File::exists($destination)){
                File::delete($destination);
            }
            $celebrity->delete();
            return redirect()->route('admin.celebrities.index')
            ->with('success', 'Celebrity has been deleted successfully.');
            
        }
        return redirect()-back()->with('message', 'Something went wrong'); 
    }

    public function services(Celebrity $celebrity)
    {
        $celebrity->load(['services.serviceType']);
        $serviceTypes = ServiceType::active()->get();
        
        return view('admin.celebrities.services', compact('celebrity', 'serviceTypes'));
    }

    public function storeService(Request $request, Celebrity $celebrity)
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_types,id|unique:celebrity_services,service_type_id,NULL,id,celebrity_id,' . $celebrity->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $celebrity->services()->create($request->all());

        return back()->with('success', 'Service has been added to celebrity.');
    }

    public function updateService(Request $request, Celebrity $celebrity, CelebrityService $service)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $service->update($request->all());

        return back()->with('success', 'Service has been updated.');
    }

    public function destroyService(Celebrity $celebrity, CelebrityService $service)
    {
        $service->delete();
        return back()->with('success', 'Service has been removed from celebrity.');
    }

    // private function uploadImage($file, $directory)
    // {
    //     $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    //     $path = $directory . '/' . $filename;
        
    //     // Resize and save image
    //     $image = Image::make($file)
    //         ->resize(800, 800, function ($constraint) {
    //             $constraint->aspectRatio();
    //             $constraint->upsize();
    //         })
    //         ->encode('jpg', 85);
            
    //     Storage::disk('public')->put($path, $image);
        
    //     return $path;
    // }

    public function toggleAvailability(Celebrity $celebrity)
    {
        $celebrity->is_active = !$celebrity->is_active;
        $celebrity->save();

        $status = $celebrity->is_active ? 'available' : 'unavailable';
        
        return redirect()->back()
            ->with('success', "Celebrity has been marked as {$status}.");
    }
}
