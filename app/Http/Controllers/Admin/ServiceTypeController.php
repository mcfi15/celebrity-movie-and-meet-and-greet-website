<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $serviceTypes = ServiceType::withCount(['celebrityServices', 'bookings'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.service-types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('admin.service-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $serviceType = new ServiceType($request->all());
        $serviceType->slug = Str::slug($request->name);
        $serviceType->save();

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type has been created successfully.');
    }

    public function show(ServiceType $serviceType)
    {
        $serviceType->load(['celebrityServices.celebrity', 'bookings']);
        return view('admin.service-types.show', compact('serviceType'));
    }

    public function edit(ServiceType $serviceType)
    {
        return view('admin.service-types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $serviceType->fill($request->all());
        
        if ($request->name !== $serviceType->getOriginal('name')) {
            $serviceType->slug = Str::slug($request->name);
        }

        $serviceType->save();

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type has been updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        // Check if service type has bookings
        if ($serviceType->bookings()->count() > 0) {
            return back()->withErrors('Cannot delete service type with existing bookings.');
        }

        $serviceType->delete();

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type has been deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:service_types,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            ServiceType::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}