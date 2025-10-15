<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Celebrity;
use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        // Stats for dashboard cards
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalCelebrities = Celebrity::count();
        $unreadMessages = ContactMessage::where('is_read', false)->count();

        // Recent bookings
        $recentBookings = Booking::with(['celebrity', 'serviceType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent messages
        $recentMessages = ContactMessage::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart data for last 30 days
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $chartData[] = Booking::whereDate('created_at', $date)->count();
        }

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingBookings', 
            'totalCelebrities',
            'unreadMessages',
            'recentBookings',
            'recentMessages',
            'chartLabels',
            'chartData'
        ));
    }

    public function showChangePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.change-password')
            ->with('success', 'Password has been changed successfully!');
    }
}