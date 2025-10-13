<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Models\SiteSetting;
use App\Mail\ContactMessageReceived;
use App\Mail\ContactMessageReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSetting();
        return view('frontend.contact', compact('settings'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $contactMessage = ContactMessage::create($validator->validated());

        // Send notification email to admin
        try {
            $settings = SiteSetting::getSetting();
            Mail::to($settings->site_email)->send(new ContactMessageReceived($contactMessage));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already subscribed or invalid.'
            ], 422);
        }

        Newsletter::create([
            'email' => $request->email,
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to our newsletter!'
        ]);
    }

    public function unsubscribe(Request $request, $email)
    {
        $newsletter = Newsletter::where('email', $email)->first();
        
        if ($newsletter) {
            $newsletter->unsubscribe();
            return view('frontend.unsubscribe-success');
        }

        return view('frontend.unsubscribe-error');
    }
}