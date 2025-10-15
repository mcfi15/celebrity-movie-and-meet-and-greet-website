<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CelebrityController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\TestimonialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/celebrities', [HomeController::class, 'celebrities'])->name('celebrities');
Route::get('/celebrity/{celebrity}', [HomeController::class, 'celebrity'])->name('celebrity.show');

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter/subscribe', [ContactController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{email}', [ContactController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Booking Routes
Route::get('/book-celebrity', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book-celebrity', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{bookingNumber}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/api/service-price', [BookingController::class, 'getServicePrice'])->name('api.service-price');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.explicit');
    
    // Change Password
    Route::get('/change-password', [AdminController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('change-password.update');
    
    // Bookings Management
    Route::resource('bookings', AdminBookingController::class);
    Route::post('bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    
    // Celebrities Management
    Route::resource('celebrities', CelebrityController::class);
    Route::patch('celebrities/{celebrity}/toggle-availability', [CelebrityController::class, 'toggleAvailability'])->name('celebrities.toggle-availability');
    
    // Service Types Management
    Route::resource('service-types', ServiceTypeController::class);
    Route::post('service-types/update-order', [ServiceTypeController::class, 'updateOrder'])->name('service-types.update-order');
    
    // Testimonials Management
    Route::resource('testimonials', TestimonialController::class);
    Route::post('testimonials/{testimonial}/toggle-featured', [TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
    Route::post('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
    
    // Contact Messages
    Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{message}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::get('contacts/{message}/reply', [AdminContactController::class, 'showReply'])->name('contacts.reply');
    Route::post('contacts/{message}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply.send');
    Route::delete('contacts/{message}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
    
    // Newsletter Management
    Route::get('newsletters', [AdminContactController::class, 'newsletters'])->name('newsletters.index');
    Route::get('newsletters/export', [AdminContactController::class, 'exportNewsletters'])->name('newsletters.export');
    Route::delete('newsletters/{newsletter}', [AdminContactController::class, 'destroyNewsletter'])->name('newsletters.destroy');
    
    // Site Settings
    Route::get('settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');
    
    // Images Management
    Route::resource('images', ImageController::class);
    Route::post('images/upload-multiple', [ImageController::class, 'uploadMultiple'])->name('images.upload-multiple');
    Route::delete('images/{image}/ajax', [ImageController::class, 'destroyAjax'])->name('images.destroy-ajax');
    Route::post('images/update-order', [ImageController::class, 'updateOrder'])->name('images.update-order');
    Route::get('images/model/{model_type}/{model_id}', [ImageController::class, 'getForModel'])->name('images.for-model');
    
    // Sliders Management
    Route::resource('sliders', SliderController::class);
    Route::post('sliders/update-order', [SliderController::class, 'updateOrder'])->name('sliders.update-order');
    Route::post('sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    
    // Payment Methods Management
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::post('payment-methods/update-order', [PaymentMethodController::class, 'updateOrder'])->name('payment-methods.update-order');
    Route::post('payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
});

// Redirect admin routes without trailing slash
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

// DEBUG: Simple test routes for payment method toggle
Route::get('/debug-payment-methods', function() {
    $paymentMethods = \App\Models\PaymentMethod::all();
    $output = '<h1>Payment Methods Debug</h1>';
    $output .= '<p>Total payment methods: ' . $paymentMethods->count() . '</p>';
    
    if ($paymentMethods->count() > 0) {
        $output .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
        $output .= '<tr><th>ID</th><th>Name</th><th>Status</th><th>Action</th></tr>';
        
        foreach ($paymentMethods as $pm) {
            $status = $pm->is_active ? 'Active' : 'Inactive';
            $output .= '<tr>';
            $output .= '<td>' . $pm->id . '</td>';
            $output .= '<td>' . $pm->name . '</td>';
            $output .= '<td>' . $status . '</td>';
            $output .= '<td><a href="/debug-toggle/' . $pm->id . '" style="padding: 5px 10px; background: #007bff; color: white; text-decoration: none;">Toggle</a></td>';
            $output .= '</tr>';
        }
        $output .= '</table>';
    } else {
        $output .= '<p>No payment methods found!</p>';
        $output .= '<p><a href="/debug-create-payment-method">Create Test Payment Method</a></p>';
    }
    
    return $output;
});

Route::get('/debug-toggle/{id}', function($id) {
    try {
        $paymentMethod = \App\Models\PaymentMethod::findOrFail($id);
        $oldStatus = $paymentMethod->is_active;
        
        $paymentMethod->is_active = !$paymentMethod->is_active;
        $paymentMethod->save();
        
        $newStatus = $paymentMethod->is_active;
        
        return '<h1>Toggle Result</h1>' .
               '<p>Payment Method: ' . $paymentMethod->name . '</p>' .
               '<p>Old Status: ' . ($oldStatus ? 'Active' : 'Inactive') . '</p>' .
               '<p>New Status: ' . ($newStatus ? 'Active' : 'Inactive') . '</p>' .
               '<p><a href="/debug-payment-methods">← Back to list</a></p>';
               
    } catch (\Exception $e) {
        return '<h1>Error</h1><p>' . $e->getMessage() . '</p><p><a href="/debug-payment-methods">← Back to list</a></p>';
    }
});

Route::get('/debug-create-payment-method', function() {
    try {
        $paymentMethod = \App\Models\PaymentMethod::create([
            'name' => 'Test Payment Method',
            'slug' => 'test-payment-method',
            'description' => 'A test payment method for debugging',
            'icon' => 'fas fa-credit-card',
            'color' => '#007bff',
            'is_active' => true,
            'processing_fee_percentage' => 2.9,
            'processing_fee_fixed' => 0.30,
            'instructions' => 'This is a test payment method.'
        ]);
        
        return '<h1>Payment Method Created</h1>' .
               '<p>ID: ' . $paymentMethod->id . '</p>' .
               '<p>Name: ' . $paymentMethod->name . '</p>' .
               '<p><a href="/debug-payment-methods">← View all payment methods</a></p>';
               
    } catch (\Exception $e) {
        return '<h1>Error Creating Payment Method</h1><p>' . $e->getMessage() . '</p>';
    }
});

// Additional Debug: Test admin route directly
Route::get('/debug-admin-route-test', function() {
    try {
        $user = auth()->user();
        $output = '<h1>Admin Route Debug</h1>';
        
        if (!$user) {
            $output .= '<p style="color: red;">❌ No user logged in</p>';
            return $output;
        }
        
        $output .= '<p>✅ User logged in: ' . $user->email . '</p>';
        $output .= '<p>User ID: ' . $user->id . '</p>';
        $output .= '<p>Is Admin: ' . (isset($user->is_admin) && $user->is_admin ? 'Yes' : 'No') . '</p>';
        
        // Test route URL generation
        try {
            $paymentMethod = \App\Models\PaymentMethod::first();
            if ($paymentMethod) {
                $toggleUrl = route('admin.payment-methods.toggle-status', $paymentMethod);
                $output .= '<p>✅ Toggle URL: ' . $toggleUrl . '</p>';
                $output .= '<p><a href="' . $toggleUrl . '" onclick="return confirm(\'Test toggle?\')">Test Toggle Route</a></p>';
            } else {
                $output .= '<p style="color: orange;">⚠️ No payment methods found to test with</p>';
            }
        } catch (\Exception $e) {
            $output .= '<p style="color: red;">❌ Route generation failed: ' . $e->getMessage() . '</p>';
        }
        
        return $output;
        
    } catch (\Exception $e) {
        return '<h1>Debug Error</h1><p>' . $e->getMessage() . '</p>';
    }
})->middleware(['auth', 'admin']);

// Email Testing Routes
Route::get('/debug-mail-config', function() {
    $output = '<h1>📧 Mail Configuration Debug</h1>';
    
    $output .= '<h2>Current Mail Settings</h2>';
    $output .= '<ul>';
    $output .= '<li><strong>Mail Driver:</strong> ' . config('mail.default') . '</li>';
    $output .= '<li><strong>Mail Host:</strong> ' . config('mail.mailers.smtp.host') . '</li>';
    $output .= '<li><strong>Mail Port:</strong> ' . config('mail.mailers.smtp.port') . '</li>';
    $output .= '<li><strong>Mail From:</strong> ' . config('mail.from.address') . '</li>';
    $output .= '<li><strong>Mail From Name:</strong> ' . config('mail.from.name') . '</li>';
    $output .= '</ul>';
    
    $output .= '<h2>Mail Templates Status</h2>';
    $output .= '<ul>';
    
    // Check if email templates exist
    $templates = [
        'booking-approved' => 'resources/views/emails/booking-approved.blade.php',
        'booking-rejected' => 'resources/views/emails/booking-rejected.blade.php',
        'booking-confirmation' => 'resources/views/emails/booking-confirmation.blade.php'
    ];
    
    foreach ($templates as $name => $path) {
        if (view()->exists("emails.{$name}")) {
            $output .= '<li style="color: green;">✅ ' . $name . ' template exists</li>';
        } else {
            $output .= '<li style="color: red;">❌ ' . $name . ' template missing</li>';
        }
    }
    $output .= '</ul>';
    
    $output .= '<h2>Test Email Sending</h2>';
    $booking = \App\Models\Booking::first();
    if ($booking) {
        $output .= '<p>Found test booking: ' . $booking->booking_number . '</p>';
        $output .= '<p><a href="/debug-test-approval-email/' . $booking->id . '" style="background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">🧪 Test Approval Email</a></p>';
        $output .= '<p><a href="/debug-test-rejection-email/' . $booking->id . '" style="background: #dc3545; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">🧪 Test Rejection Email</a></p>';
    } else {
        $output .= '<p style="color: orange;">⚠️ No bookings found to test with</p>';
    }
    
    return $output;
});

Route::get('/debug-test-approval-email/{booking}', function(\App\Models\Booking $booking) {
    try {
        \Log::info('Debug: Testing approval email for booking ' . $booking->id);
        
        \Illuminate\Support\Facades\Mail::to($booking->customer_email)
            ->send(new \App\Mail\BookingApproved($booking));
        
        return '<h1>✅ Test Email Sent!</h1>' .
               '<p>Approval email sent to: ' . $booking->customer_email . '</p>' .
               '<p>Booking: ' . $booking->booking_number . '</p>' .
               '<p>Check your email client or Mailpit (http://localhost:8025) for the email.</p>' .
               '<p><a href="/debug-mail-config">← Back to Mail Config</a></p>';
               
    } catch (\Exception $e) {
        \Log::error('Debug: Approval email test failed: ' . $e->getMessage());
        
        return '<h1>❌ Email Test Failed</h1>' .
               '<p>Error: ' . $e->getMessage() . '</p>' .
               '<p>Check Laravel logs for more details.</p>' .
               '<p><a href="/debug-mail-config">← Back to Mail Config</a></p>';
    }
})->middleware(['auth', 'admin']);

Route::get('/debug-test-rejection-email/{booking}', function(\App\Models\Booking $booking) {
    try {
        \Log::info('Debug: Testing rejection email for booking ' . $booking->id);
        
        \Illuminate\Support\Facades\Mail::to($booking->customer_email)
            ->send(new \App\Mail\BookingRejected($booking));
        
        return '<h1>✅ Test Email Sent!</h1>' .
               '<p>Rejection email sent to: ' . $booking->customer_email . '</p>' .
               '<p>Booking: ' . $booking->booking_number . '</p>' .
               '<p>Check your email client or Mailpit (http://localhost:8025) for the email.</p>' .
               '<p><a href="/debug-mail-config">← Back to Mail Config</a></p>';
               
    } catch (\Exception $e) {
        \Log::error('Debug: Rejection email test failed: ' . $e->getMessage());
        
        return '<h1>❌ Email Test Failed</h1>' .
               '<p>Error: ' . $e->getMessage() . '</p>' .
               '<p>Check Laravel logs for more details.</p>' .
               '<p><a href="/debug-mail-config">← Back to Mail Config</a></p>';
    }
})->middleware(['auth', 'admin']);
