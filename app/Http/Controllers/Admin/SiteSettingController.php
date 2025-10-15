<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Celebrity;
use App\Models\ServiceType;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $siteSetting = SiteSetting::getSetting();
        
        // Convert the model to array format expected by the view
        $settings = [
            'site_name' => $siteSetting->site_name,
            'site_tagline' => $siteSetting->site_tagline ?? '',
            'site_description' => $siteSetting->site_description,
            'contact_email' => $siteSetting->site_email,
            'contact_phone' => $siteSetting->site_phone,
            'contact_address' => $siteSetting->site_address,
            'payment_enabled' => $siteSetting->payment_enabled,
            'stripe_enabled' => $siteSetting->stripe_enabled ?? false,
            'crypto_enabled' => $siteSetting->crypto_enabled ?? false,
            'bank_transfer_enabled' => $siteSetting->bank_transfer_enabled ?? false,
            'paypal_enabled' => $siteSetting->paypal_enabled ?? false,
            'cash_enabled' => $siteSetting->cash_enabled ?? false,
            'site_logo' => $siteSetting->site_logo,
        ];

        // Statistics for the sidebar
        $stats = [
            'celebrities' => Celebrity::count(),
            'bookings' => Booking::count(),
            'services' => ServiceType::count(),
            'messages' => ContactMessage::count(),
        ];

        return view('admin.settings.index', compact('settings', 'stats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_enabled' => 'nullable|boolean',
            'stripe_enabled' => 'nullable|boolean',
            'crypto_enabled' => 'nullable|boolean',
            'bank_transfer_enabled' => 'nullable|boolean',
            'paypal_enabled' => 'nullable|boolean',
            'cash_enabled' => 'nullable|boolean',
        ]);

        $siteSetting = SiteSetting::getSetting();
        
        // Prepare update data
        $updateData = [
            'site_name' => $request->site_name,
            'site_tagline' => $request->site_tagline,
            'site_description' => $request->site_description,
            'site_email' => $request->contact_email,
            'site_phone' => $request->contact_phone,
            'site_address' => $request->contact_address,
            'payment_enabled' => $request->has('payment_enabled'),
            'stripe_enabled' => $request->has('stripe_enabled'),
            'crypto_enabled' => $request->has('crypto_enabled'),
            'bank_transfer_enabled' => $request->has('bank_transfer_enabled'),
            'paypal_enabled' => $request->has('paypal_enabled'),
            'cash_enabled' => $request->has('cash_enabled'),
        ];

        if($request->hasFile('site_logo')){
 
            $path = $siteSetting->site_logo;
            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('site_logo');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/logo/',$filename);
            $updateData['site_logo'] = "uploads/logo/$filename";
        }

        if($request->hasFile('site_favicon')){
 
            $path = $siteSetting->site_favicon;
            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('site_favicon');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/favicon/',$filename);
            $updateData['site_favicon'] = "uploads/favicon/$filename";
        }

        // Build payment methods array based on enabled options
        $paymentMethods = [];
        if ($request->has('stripe_enabled')) {
            $paymentMethods[] = 'stripe';
        }
        if ($request->has('crypto_enabled')) {
            $paymentMethods[] = 'crypto';
        }
        if ($request->has('bank_transfer_enabled')) {
            $paymentMethods[] = 'bank_transfer';
        }
        if ($request->has('paypal_enabled')) {
            $paymentMethods[] = 'paypal';
        }
        if ($request->has('cash_enabled')) {
            $paymentMethods[] = 'cash';
        }
        
        $updateData['payment_methods'] = $paymentMethods;

        // Update the settings
        $siteSetting->update($updateData);

        return back()->with('success', 'Site settings have been updated successfully.');
    }

    private function uploadImage($file, $directory, $width = null, $height = null)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $filename;
        
        if ($width && $height) {
            $image = Image::make($file)
                ->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('png', 90);
        } else {
            $image = Image::make($file)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 85);
        }
            
        Storage::disk('public')->put($path, $image);
        
        return $path;
    }
}