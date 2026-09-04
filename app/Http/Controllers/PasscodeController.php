<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasscodeController extends Controller
{
    public function showEntryForm()
    {
        $settings = SiteSetting::getSetting();

        return view('frontend.passcode-entry', compact('settings'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'passcode' => 'required|string',
        ]);

        $setting = SiteSetting::getSetting();

        if (!$setting->passcode_enabled || empty($setting->site_passcode)) {
            return redirect()->intended(route('home'));
        }

        if (Hash::check($request->passcode, $setting->site_passcode)) {
            $request->session()->put('site_passcode_verified', true);

            return redirect()->intended(route('home'))
                ->with('success', 'Welcome!');
        }

        return back()
            ->withErrors(['passcode' => 'The passcode you entered is incorrect.'])
            ->withInput($request->except('passcode'));
    }
}