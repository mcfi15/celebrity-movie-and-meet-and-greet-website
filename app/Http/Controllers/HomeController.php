<?php

namespace App\Http\Controllers;

use App\Models\Celebrity;
use App\Models\ServiceType;
use App\Models\Testimonial;
use App\Models\SiteSetting;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSetting();
        $sliders = Slider::active()
            ->ordered()
            ->get();

        $featuredCelebrities = Celebrity::active()
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        $popularServices = ServiceType::active()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::active()
            ->featured()
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();

        return view('frontend.home', compact(
            'sliders',
            'featuredCelebrities',
            'popularServices', 
            'testimonials',
            'settings'
        ));
    }

    public function about()
    {
        $settings = SiteSetting::getSetting();
        $totalCelebrities = Celebrity::active()->count();
        $totalServices = ServiceType::active()->count();
        $testimonials = Testimonial::active()->take(6)->get();

        return view('frontend.about', compact(
            'settings',
            'totalCelebrities',
            'totalServices',
            'testimonials'
        ));
    }

    public function services()
    {
        $services = ServiceType::active()
            ->ordered()
            ->with(['celebrityServices' => function($query) {
                $query->available()
                    ->with('celebrity')
                    ->orderBy('price')
                    ->take(3);
            }])
            ->get();

        return view('frontend.services', compact('services'));
    }

    public function celebrities()
    {
        $celebrities = Celebrity::active()
            ->with(['services.serviceType'])
            ->orderBy('rating', 'desc')
            ->orderBy('name')
            ->paginate(12);

        return view('frontend.celebrities', compact('celebrities'));
    }

    public function celebrity($slug)
    {
        $celebrity = Celebrity::where('slug', $slug)
            ->where('is_active', true)
            ->with(['availableServices.serviceType'])
            ->firstOrFail();

        $relatedCelebrities = Celebrity::active()
            ->where('id', '!=', $celebrity->id)
            ->where('profession', $celebrity->profession)
            ->take(3)
            ->get();

        return view('frontend.celebrity-detail', compact('celebrity', 'relatedCelebrities'));
    }
}