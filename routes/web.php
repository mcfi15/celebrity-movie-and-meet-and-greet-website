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
});

// Redirect admin routes without trailing slash
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
