<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Celebrity;
use App\Models\ServiceType;
use App\Models\SiteSetting;
use App\Models\PaymentMethod;
use App\Models\CryptoWallet;
use App\Mail\BookingConfirmation;
use App\Mail\BookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $celebrities = Celebrity::active()
            ->orderBy('name')
            ->get();

        $serviceTypes = ServiceType::active()
            ->ordered()
            ->get();

        $settings = SiteSetting::getSetting();

        // Only expose payment methods that the admin has enabled in Site Settings.
        // If none are explicitly allowed, fall back to all active methods.
        $allowedPaymentMethods = $settings->payment_methods ?? [];
        $paymentMethods = PaymentMethod::active()
            ->when(!empty($allowedPaymentMethods), function ($query) use ($allowedPaymentMethods) {
                return $query->whereIn('slug', $allowedPaymentMethods);
            })
            ->ordered()
            ->get();

        $paymentEnabled = (bool) $settings->payment_enabled;

        $cryptoWallets = CryptoWallet::active()
            ->ordered()
            ->get();

        // Pre-fill if celebrity and service are provided
        $selectedCelebrity = null;
        $selectedService = null;
        
        if ($request->celebrity) {
            $selectedCelebrity = Celebrity::where('slug', $request->celebrity)
                ->orWhere('id', $request->celebrity)
                ->first();
        }
        
        if ($request->service) {
            $selectedService = ServiceType::where('slug', $request->service)
                ->orWhere('id', $request->service)
                ->first();
        }

        return view('frontend.booking.create', compact(
            'celebrities',
            'serviceTypes',
            'paymentMethods',
            'cryptoWallets',
            'settings',
            'paymentEnabled',
            'selectedCelebrity',
            'selectedService'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'celebrity_id' => 'required|exists:celebrities,id',
            'service_type_id' => 'required|exists:service_types,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_message' => 'nullable|string|max:2000',
            'event_date' => 'required|date|after:today',
            'event_location' => 'nullable|string|max:500',
            'event_details' => 'nullable|string|max:2000',
            'duration_hours' => 'required|integer|min:1|max:24',
            'payment_method' => 'nullable|string|exists:payment_methods,slug',
            'crypto_wallet_id' => 'nullable|integer|exists:crypto_wallets,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // A crypto wallet is required when the crypto payment method is chosen
        if ($request->payment_method === 'crypto' && !$request->filled('crypto_wallet_id')) {
            return back()
                ->withErrors(['crypto_wallet_id' => 'Please select a crypto wallet to continue with cryptocurrency payment.'])
                ->withInput();
        }

        // Reject payment methods that the admin has not enabled in Site Settings
        $settings = SiteSetting::getSetting();
        if ($request->filled('payment_method')) {
            if (!$settings->payment_enabled) {
                return back()
                    ->withErrors(['payment_method' => 'Online payments are currently disabled. Please select "Pay Later" or contact support.'])
                    ->withInput();
            }
            $allowedPaymentMethods = $settings->payment_methods ?? [];
            if (!empty($allowedPaymentMethods) && !in_array($request->payment_method, $allowedPaymentMethods)) {
                return back()
                    ->withErrors(['payment_method' => 'The selected payment method is not currently available. Please choose another method.'])
                    ->withInput();
            }
        }

        $celebrity = Celebrity::findOrFail($request->celebrity_id);
        $serviceType = ServiceType::findOrFail($request->service_type_id);
        
        // Get celebrity service price or calculate from base prices
        $celebrityService = $celebrity->services()
            ->where('service_type_id', $request->service_type_id)
            ->first();
        
        if ($celebrityService && $celebrityService->price > 0) {
            $basePrice = $celebrityService->price;
        } else {
            // Fallback pricing logic with null safety
            $celebrityPrice = $celebrity->hourly_rate ?? $celebrity->base_price ?? 0;
            $servicePrice = $serviceType->base_price ?? 0;
            
            // Use celebrity's rate if available, otherwise combine both
            $basePrice = $celebrityPrice > 0 ? $celebrityPrice : ($celebrityPrice + $servicePrice);
            
            // Ensure we have a minimum price if all else fails
            if ($basePrice <= 0) {
                $basePrice = 100; // Default minimum price
            }
        }
        
        // Calculate total based on duration
        $totalAmount = $basePrice * $request->duration_hours;
        
        $bookingData = array_merge($validator->validated(), [
            'user_id' => Auth::id(),
            'base_price' => $basePrice,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // A crypto wallet is only valid when the crypto payment method is chosen
        if ($request->payment_method !== 'crypto' || !$request->crypto_wallet_id) {
            $bookingData['crypto_wallet_id'] = null;
        }

        $booking = Booking::create($bookingData);
        
        // Handle payment processing based on selected method
        if (!empty($request->payment_method)) {
            $paymentMethod = PaymentMethod::where('slug', $request->payment_method)->first();
            
            if ($paymentMethod) {
                // Here you would integrate with actual payment processors
                // For now, we'll simulate the payment process
                
                switch ($paymentMethod->slug) {
                    case 'stripe':
                        // Integrate with Stripe
                        $booking->payment_status = 'processing';
                        $booking->save();
                        break;
                        
                    case 'paypal':
                        // Integrate with PayPal
                        $booking->payment_status = 'processing';
                        $booking->save();
                        break;
                        
                    case 'crypto':
                        // Crypto payments wait for proof submission.
                        // A crypto_wallet_id must be assigned before submitting proof.
                        $booking->payment_status = 'pending';
                        $booking->save();
                        break;
                        
                    case 'cash':
                        // Cash payment - remains pending until admin confirmation
                        $booking->payment_status = 'pending';
                        $booking->save();
                        break;
                        
                    default:
                        // Other payment methods
                        $booking->payment_status = 'pending';
                        $booking->save();
                        break;
                }
            }
        }

        // Send confirmation email to customer
        try {
            Mail::to($booking->customer_email)->send(new BookingConfirmation($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }

        // Send notification email to admin
        try {
            $settings = SiteSetting::getSetting();
            Mail::to($settings->site_email)->send(new BookingNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking notification email: ' . $e->getMessage());
        }

        return redirect()->route('booking.success', $booking->booking_number)
            ->with('success', 'Your booking request has been submitted successfully!');
    }

    public function success($bookingNumber)
    {
        $booking = Booking::where('booking_number', $bookingNumber)
            ->with(['celebrity', 'serviceType', 'cryptoWallet'])
            ->firstOrFail();

        $settings = SiteSetting::getSetting();

        return view('frontend.booking.success', compact('booking', 'settings'));
    }

    public function submitPaymentProof(Request $request, Booking $booking)
    {
        $request->validate([
            'customer_email' => 'required|email',
            'payment_tx_hash' => 'required|string|max:255',
            'payment_proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Basic ownership guard: the email must match the booking's email.
        if (strtolower($request->customer_email) !== strtolower($booking->customer_email)) {
            return back()
                ->withErrors(['customer_email' => 'The email address does not match the one used for this booking.'])
                ->withInput($request->except('payment_proof_image'));
        }

        if (!$booking->crypto_wallet_id || $booking->payment_status === 'paid') {
            return back()->with('error', 'This booking is not eligible for crypto payment submission.');
        }

        $data = [
            'payment_tx_hash' => $request->payment_tx_hash,
            'status' => 'pending_payment_verification',
            'payment_status' => 'pending_verification',
            'payment_submitted_at' => now(),
        ];

        if ($request->hasFile('payment_proof_image')) {
            $filename = Str::random(24) . '.' . $request->file('payment_proof_image')->getClientOriginalExtension();
            $data['payment_proof_image'] = $request->file('payment_proof_image')
                ->storeAs('payment-proofs', $filename, 'public');
        }

        $booking->update($data);

        return back()->with('success', 'Payment proof submitted successfully. Our team will verify your payment shortly.');
    }

    public function getServicePrice(Request $request)
    {
        $celebrity = Celebrity::find($request->celebrity_id);
        $serviceType = ServiceType::find($request->service_type_id);
        
        if (!$celebrity || !$serviceType) {
            return response()->json([
                'price' => 0,
                'formatted_price' => '$0.00',
                'error' => 'Celebrity or service type not found'
            ]);
        }
        
        // First, check if there's a specific celebrity service with pricing
        $celebrityService = $celebrity->services()
            ->where('service_type_id', $request->service_type_id)
            ->first();
        
        if ($celebrityService && $celebrityService->price > 0) {
            $price = $celebrityService->price;
        } else {
            // Fallback to celebrity's hourly rate or base price plus service base price
            $celebrityPrice = $celebrity->hourly_rate ?? $celebrity->base_price ?? 0;
            $servicePrice = $serviceType->base_price ?? 0;
            
            // Use celebrity's rate if available, otherwise combine both
            $price = $celebrityPrice > 0 ? $celebrityPrice : ($celebrityPrice + $servicePrice);
            
            // Ensure we have a minimum price
            if ($price <= 0) {
                $price = 100; // Default minimum price if nothing is set
            }
        }
        
        return response()->json([
            'price' => $price,
            'formatted_price' => '$' . number_format($price, 2),
            'celebrity_base' => $celebrity->hourly_rate ?? $celebrity->base_price ?? 0,
            'service_base' => $serviceType->base_price ?? 0
        ]);
    }
}
