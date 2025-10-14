<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Celebrity;
use App\Models\ServiceType;
use App\Mail\BookingApproved;
use App\Mail\BookingRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Booking::with(['celebrity', 'serviceType'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->celebrity) {
            $query->where('celebrity_id', $request->celebrity);
        }

        if ($request->service) {
            $query->where('service_type_id', $request->service);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('celebrity', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(15);

        // Stats for cards
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();

        // For filter dropdowns
        $celebrities = Celebrity::orderBy('name')->get();
        $serviceTypes = ServiceType::orderBy('name')->get();

        return view('admin.bookings.index', compact(
            'bookings',
            'totalBookings',
            'pendingBookings', 
            'approvedBookings',
            'rejectedBookings',
            'celebrities',
            'serviceTypes'
        ));
    }

    public function create()
    {
        $celebrities = Celebrity::where('is_active', true)->orderBy('name')->get();
        $serviceTypes = ServiceType::orderBy('name')->get();
        
        return view('admin.bookings.create', compact('celebrities', 'serviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'celebrity_id' => 'required|exists:celebrities,id',
            'service_type_id' => 'required|exists:service_types,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:255',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'event_location' => 'required|string|max:500',
            'event_description' => 'required|string|max:1000',
            'status' => 'nullable|in:pending,approved,rejected,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'special_requests' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Set default values if not provided
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['payment_status'] = $validated['payment_status'] ?? 'pending';

        Booking::create($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been created successfully.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['celebrity', 'serviceType', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $celebrities = Celebrity::orderBy('name')->get();
        $serviceTypes = ServiceType::orderBy('name')->get();
        
        return view('admin.bookings.edit', compact('booking', 'celebrities', 'serviceTypes'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'celebrity_id' => 'required|exists:celebrities,id',
            'service_type_id' => 'required|exists:service_types,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'event_location' => 'required|string|max:500',
            'event_description' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'special_requests' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been updated successfully.');
    }

    public function approve(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_at' => now(),
        ]);

        // Send approval email
        try {
            Mail::to($booking->customer_email)->send(new BookingApproved($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking has been approved and customer has been notified.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'rejected_at' => now(),
        ]);

        // Send rejection email
        try {
            Mail::to($booking->customer_email)->send(new BookingRejected($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking rejection email: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking has been rejected and customer has been notified.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update($request->only(['status', 'payment_status', 'admin_notes']));

        return back()->with('success', 'Booking status has been updated.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been deleted.');
    }
}