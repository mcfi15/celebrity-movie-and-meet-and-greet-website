<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->get();
        
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:payment_methods',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'processing_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'processing_fee_fixed' => 'nullable|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0|gte:minimum_amount',
            'instructions' => 'nullable|string|max:2000',
            'api_key' => 'nullable|string|max:500',
            'api_secret' => 'nullable|string|max:1000',
            'webhook_url' => 'nullable|url|max:500',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (PaymentMethod::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data['is_active'] = $request->has('is_active');

        PaymentMethod::create($data);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function show(PaymentMethod $paymentMethod)
    {
        $bookingsCount = $paymentMethod->bookings()->count();
        $totalRevenue = $paymentMethod->bookings()
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return view('admin.payment-methods.show', compact('paymentMethod', 'bookingsCount', 'totalRevenue'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('payment_methods')->ignore($paymentMethod->id)],
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'processing_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'processing_fee_fixed' => 'nullable|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0|gte:minimum_amount',
            'instructions' => 'nullable|string|max:2000',
            'api_key' => 'nullable|string|max:500',
            'api_secret' => 'nullable|string|max:1000',
            'webhook_url' => 'nullable|url|max:500',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure unique slug (excluding current record)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (PaymentMethod::where('slug', $data['slug'])->where('id', '!=', $paymentMethod->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data['is_active'] = $request->has('is_active');

        $paymentMethod->update($data);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if payment method is being used
        $bookingsCount = $paymentMethod->bookings()->count();
        
        if ($bookingsCount > 0) {
            return back()->with('error', 'Cannot delete payment method that has been used in bookings. Deactivate it instead.');
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'payment_method_ids' => 'required|array',
            'payment_method_ids.*' => 'exists:payment_methods,id',
        ]);

        foreach ($request->payment_method_ids as $index => $id) {
            PaymentMethod::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment method order updated successfully.'
        ]);
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        \Log::info('PaymentMethod toggleStatus called', [
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'payment_method_id' => $paymentMethod->id,
            'current_status' => $paymentMethod->is_active,
            'user_id' => auth()->id(),
            'is_ajax' => request()->ajax(),
            'csrf_token' => request()->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        try {
            $oldStatus = $paymentMethod->is_active;
            $newStatus = !$paymentMethod->is_active;
            
            $paymentMethod->update(['is_active' => $newStatus]);
            
            // Refresh from database to confirm the change
            $paymentMethod->refresh();
            
            \Log::info('Payment method toggle successful', [
                'id' => $paymentMethod->id,
                'name' => $paymentMethod->name,
                'old_status' => $oldStatus ? 'active' : 'inactive',
                'new_status' => $paymentMethod->is_active ? 'active' : 'inactive',
                'confirmed_from_db' => $paymentMethod->is_active
            ]);

            $message = 'Payment method "' . $paymentMethod->name . '" ' . 
                      ($paymentMethod->is_active ? 'activated' : 'deactivated') . ' successfully.';

            // Return JSON for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'is_active' => $paymentMethod->is_active,
                    'message' => $message,
                    'debug' => [
                        'old_status' => $oldStatus,
                        'new_status' => $paymentMethod->is_active,
                        'timestamp' => now()->toISOString()
                    ]
                ]);
            }

            // Redirect for form requests
            return redirect()->route('admin.payment-methods.index')
                            ->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Payment method toggle failed', [
                'id' => $paymentMethod->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Failed to update payment method status: ' . $e->getMessage();

            // Return JSON for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug' => [
                        'error_type' => get_class($e),
                        'error_line' => $e->getLine(),
                        'error_file' => $e->getFile()
                    ]
                ], 500);
            }

            // Redirect for form requests
            return redirect()->route('admin.payment-methods.index')
                            ->with('error', $errorMessage);
        }
    }
}
