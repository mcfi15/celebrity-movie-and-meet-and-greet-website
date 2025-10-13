@extends('layouts.app')

@section('title', 'Our Services - Celebrity Booking')
@section('description', 'Explore our premium celebrity booking services including meet & greets, autograph sessions, corporate events, and exclusive experiences.')

@section('content')
<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Our <span class="gradient-text">Premium Services</span>
                </h1>
                <p class="lead text-not-muted mb-4">
                    From intimate meet & greets to grand corporate events, we offer a wide range of celebrity 
                    booking services tailored to create unforgettable experiences for every occasion.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold">
                        <i class="fas fa-calendar-plus me-2"></i> Book Service
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-gold">
                        <i class="fas fa-question-circle me-2"></i> Get Quote
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Celebrity Services" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient rounded-3" style="background: linear-gradient(45deg, rgba(255,215,0,0.1), rgba(26,26,26,0.3));"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5">
    <div class="container">
        @if($services->count() > 0)
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="h1 fw-bold mb-4">What We Offer</h2>
                    <p class="lead text-not-muted">
                        Choose from our comprehensive range of celebrity booking services, 
                        each designed to deliver exceptional experiences.
                    </p>
                </div>
            </div>
            
            <div class="row">
                @foreach($services as $service)
                    <div class="col-lg-4 col-md-6 mb-5">
                        <div class="card bg-card h-100 service-card">
                            <div class="card-body p-4">
                                <!-- Service Icon -->
                                <div class="text-center mb-4">
                                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        @switch($service->name)
                                            @case('Meet & Greet')
                                                <i class="fas fa-handshake fs-3 text-light"></i>
                                                @break
                                            @case('Autograph Session')
                                                <i class="fas fa-signature fs-3 text-light"></i>
                                                @break
                                            @case('Corporate Event')
                                                <i class="fas fa-briefcase fs-3 text-light"></i>
                                                @break
                                            @case('Private Event')
                                                <i class="fas fa-user-friends fs-3 text-light"></i>
                                                @break
                                            @case('Virtual Meeting')
                                                <i class="fas fa-video fs-3 text-light"></i>
                                                @break
                                            @case('Photo Session')
                                                <i class="fas fa-camera fs-3 text-light"></i>
                                                @break
                                            @default
                                                <i class="fas fa-star fs-3 text-light"></i>
                                        @endswitch
                                    </div>
                                    <h4 class="fw-bold mb-2">{{ $service->name }}</h4>
                                </div>
                                
                                <!-- Service Description -->
                                <p class="text-not-muted mb-4">{{ $service->description }}</p>
                                
                                <!-- Price Range -->
                                @if($service->celebrityServices->count() > 0)
                                    <div class="mb-4">
                                        @php
                                            $prices = $service->celebrityServices->pluck('price');
                                            $minPrice = $prices->min();
                                            $maxPrice = $prices->max();
                                        @endphp
                                        
                                        <div class="text-center">
                                            <span class="text-not-muted small">Starting from</span>
                                            <div class="fs-4 fw-bold text-gold">
                                                @if($minPrice == $maxPrice)
                                                    ${{ number_format($minPrice) }}
                                                @else
                                                    ${{ number_format($minPrice) }} - ${{ number_format($maxPrice) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Available Celebrities -->
                                @if($service->celebrityServices->count() > 0)
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-3">Featured Celebrities</h6>
                                        <div class="row g-2">
                                            @foreach($service->celebrityServices->take(3) as $celebrityService)
                                                <div class="col-4">
                                                    <div class="text-center">
                                                        @if($celebrityService->celebrity->image)
                                                            <img src="{{ Storage::url($celebrityService->celebrity->image) }}" 
                                                                 alt="{{ $celebrityService->celebrity->name }}"
                                                                 class="rounded-circle mb-1" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width: 50px; height: 50px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div class="small text-not-muted">{{ Str::limit($celebrityService->celebrity->name, 10) }}</div>
                                                        <div class="small text-gold">${{ number_format($celebrityService->price) }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if($service->celebrityServices->count() > 3)
                                            <div class="text-center mt-2">
                                                <small class="text-not-muted">+{{ $service->celebrityServices->count() - 3 }} more celebrities available</small>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- CTA Button -->
                                <div class="text-center">
                                    <a href="{{ route('booking.create') }}?service={{ $service->id }}" class="btn btn-gold w-100">
                                        <i class="fas fa-calendar-plus me-2"></i> Book This Service
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-concierge-bell fa-3x text-not-muted mb-3"></i>
                <h3 class="text-not-muted">No Services Available</h3>
                <p class="text-not-muted">We're working on adding amazing services for you. Please check back soon!</p>
                <a href="{{ route('contact') }}" class="btn btn-gold">
                    <i class="fas fa-envelope me-2"></i> Contact Us
                </a>
            </div>
        @endif
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">How It Works</h2>
                <p class="lead text-not-muted">
                    Booking your celebrity experience is simple and straightforward. Follow these easy steps.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">1</span>
                    </div>
                    <h5 class="fw-bold mb-3">Choose Service</h5>
                    <p class="text-not-muted small">
                        Browse our services and select the type of experience you want.
                    </p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">2</span>
                    </div>
                    <h5 class="fw-bold mb-3">Select Celebrity</h5>
                    <p class="text-not-muted small">
                        Pick your favorite celebrity from our exclusive roster.
                    </p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">3</span>
                    </div>
                    <h5 class="fw-bold mb-3">Book & Pay</h5>
                    <p class="text-not-muted small">
                        Complete your booking with our secure payment system.
                    </p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">4</span>
                    </div>
                    <h5 class="fw-bold mb-3">Enjoy Experience</h5>
                    <p class="text-not-muted small">
                        Meet your celebrity and create unforgettable memories.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="h1 fw-bold mb-4">Why Our Services Stand Out</h2>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">100% Authentic</h6>
                                <p class="text-not-muted small">All celebrities are verified and genuine.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">Flexible Scheduling</h6>
                                <p class="text-not-muted small">Book at your convenience with flexible timing.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-headset text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">24/7 Support</h6>
                                <p class="text-not-muted small">Round-the-clock customer support.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">Best Value</h6>
                                <p class="text-not-muted small">Competitive pricing with premium service.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="Premium Service" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-bold mb-4">Ready to Book Your Experience?</h2>
                <p class="lead text-not-muted mb-4">
                    Don't wait! Our celebrity bookings fill up quickly. Reserve your spot today and 
                    create memories that will last a lifetime.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i> Book Now
                    </a>
                    <a href="{{ route('celebrities') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-users me-2"></i> View Celebrities
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.page-hero {
    padding: 100px 0;
}

.bg-section {
    background-color: rgba(255, 255, 255, 0.02);
}

.gradient-text {
    background: linear-gradient(135deg, #ffd700, #ffed4a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.service-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(255, 215, 0, 0.1);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(255, 215, 0, 0.2);
    border-color: rgba(255, 215, 0, 0.3);
}
</style>
@endpush
