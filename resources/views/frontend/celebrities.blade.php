@extends('layouts.app')

@section('title', 'Our Celebrities - Celebrity Booking Agency')
@section('description', 'Browse our exclusive roster of A-list celebrities available for bookings. From Hollywood stars to sports legends and influencers.')

@section('content')
<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Meet Our <span class="gradient-text">Celebrity Roster</span>
                </h1>
                <p class="lead text-not-muted mb-4">
                    Discover our exclusive collection of A-list celebrities, sports legends, influencers, and entertainment icons. 
                    Each one ready to create unforgettable experiences just for you.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold">
                        <i class="fas fa-calendar-plus me-2"></i> Book Experience
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-outline-gold">
                        <i class="fas fa-concierge-bell me-2"></i> View Services
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Celebrities" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient rounded-3" style="background: linear-gradient(45deg, rgba(255,215,0,0.1), rgba(26,26,26,0.3));"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-4">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-users text-gold me-2"></i>
                    <span class="fw-bold">{{ $celebrities->total() }} Celebrities</span>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-star text-gold me-2"></i>
                    <span class="fw-bold">A-List Talent</span>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-shield-alt text-gold me-2"></i>
                    <span class="fw-bold">100% Verified</span>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-clock text-gold me-2"></i>
                    <span class="fw-bold">Quick Booking</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Celebrity Grid -->
<section class="py-5">
    <div class="container">
        @if($celebrities->count() > 0)
            <div class="row">
                @foreach($celebrities as $celebrity)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card bg-card h-100 celebrity-card">
                            <div class="position-relative">
                                @if($celebrity->image)
                                    <img src="{{ Storage::url($celebrity->image) }}" 
                                         class="card-img-top celebrity-image" 
                                         alt="{{ $celebrity->name }}"
                                         style="height: 300px; object-fit: cover;">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary" style="height: 300px;">
                                        <i class="fas fa-user fa-4x text-white"></i>
                                    </div>
                                @endif
                                
                                <!-- Rating Badge -->
                                @if($celebrity->rating)
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-gold text-light fs-6">
                                            <i class="fas fa-star"></i> {{ number_format($celebrity->rating, 1) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Available
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <!-- Celebrity Name & Profession -->
                                <h5 class="card-title fw-bold mb-2">{{ $celebrity->name }}</h5>
                                <p class="text-gold mb-3">{{ $celebrity->profession }}</p>
                                
                                <!-- Bio Preview -->
                                @if($celebrity->bio)
                                    <p class="text-not-muted mb-3">{{ Str::limit($celebrity->bio, 120) }}</p>
                                @endif
                                
                                <!-- Services & Pricing -->
                                @if($celebrity->services->count() > 0)
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-2">Available Services</h6>
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            @foreach($celebrity->services->take(3) as $service)
                                                <span class="badge bg-outline-gold small">{{ $service->serviceType->name }}</span>
                                            @endforeach
                                            @if($celebrity->services->count() > 3)
                                                <span class="badge bg-secondary small">+{{ $celebrity->services->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Price Range -->
                                        @php
                                            $prices = $celebrity->services->where('is_available', true)->pluck('price');
                                            $minPrice = $prices->min();
                                            $maxPrice = $prices->max();
                                        @endphp
                                        
                                        @if($minPrice)
                                            <div class="text-center">
                                                <span class="text-not-muted small">Starting from</span>
                                                <div class="fs-5 fw-bold text-gold">
                                                    @if($minPrice == $maxPrice)
                                                        ${{ number_format($minPrice) }}
                                                    @else
                                                        ${{ number_format($minPrice) }} - ${{ number_format($maxPrice) }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <a href="{{ route('celebrity.show', $celebrity->slug) }}" class="btn btn-gold">
                                        <i class="fas fa-eye me-2"></i> View Profile
                                    </a>
                                    <a href="{{ route('booking.create') }}?celebrity={{ $celebrity->id }}" class="btn btn-outline-gold">
                                        <i class="fas fa-calendar-plus me-2"></i> Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $celebrities->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-not-muted mb-3"></i>
                <h3 class="text-not-muted">No Celebrities Available</h3>
                <p class="text-not-muted">We're working on adding amazing celebrities to our roster. Please check back soon!</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('contact') }}" class="btn btn-gold">
                        <i class="fas fa-envelope me-2"></i> Contact Us
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-outline-gold">
                        <i class="fas fa-concierge-bell me-2"></i> View Services
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">Celebrity Categories</h2>
                <p class="lead text-not-muted">
                    From Hollywood A-listers to sports legends, we have celebrities from every industry.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-card text-center">
                    <div class="card-body">
                        <i class="fas fa-film text-gold fs-1 mb-3"></i>
                        <h5 class="fw-bold">Actors & Actresses</h5>
                        <p class="text-not-muted small">Hollywood stars and film icons</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-card text-center">
                    <div class="card-body">
                        <i class="fas fa-music text-gold fs-1 mb-3"></i>
                        <h5 class="fw-bold">Musicians</h5>
                        <p class="text-not-muted small">Chart-topping artists and bands</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-card text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy text-gold fs-1 mb-3"></i>
                        <h5 class="fw-bold">Sports Legends</h5>
                        <p class="text-not-muted small">Professional athletes and champions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-card text-center">
                    <div class="card-body">
                        <i class="fas fa-tv text-gold fs-1 mb-3"></i>
                        <h5 class="fw-bold">TV Personalities</h5>
                        <p class="text-not-muted small">Television hosts and reality stars</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">How Celebrity Booking Works</h2>
                <p class="lead text-not-muted">
                    Booking your favorite celebrity is easy and straightforward.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">1</span>
                    </div>
                    <h5 class="fw-bold mb-3">Browse Celebrities</h5>
                    <p class="text-not-muted small">
                        Explore our roster and find your favorite celebrity.
                    </p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">2</span>
                    </div>
                    <h5 class="fw-bold mb-3">Select Service</h5>
                    <p class="text-not-muted small">
                        Choose from available services and packages.
                    </p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">3</span>
                    </div>
                    <h5 class="fw-bold mb-3">Complete Booking</h5>
                    <p class="text-not-muted small">
                        Fill out details and secure your booking.
                    </p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-4 fw-bold text-light">4</span>
                    </div>
                    <h5 class="fw-bold mb-3">Meet Your Star</h5>
                    <p class="text-not-muted small">
                        Enjoy your exclusive celebrity experience.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-bold mb-4">Ready to Meet Your Celebrity?</h2>
                <p class="lead text-not-muted mb-4">
                    Don't miss out! Our celebrity bookings are limited and fill up quickly. 
                    Secure your spot today for an unforgettable experience.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i> Book Experience
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-question-circle me-2"></i> Ask Questions
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

.celebrity-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(255, 215, 0, 0.1);
}

.celebrity-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(255, 215, 0, 0.2);
    border-color: rgba(255, 215, 0, 0.3);
}

.celebrity-image {
    transition: transform 0.3s ease;
}

.celebrity-card:hover .celebrity-image {
    transform: scale(1.05);
}

.bg-outline-gold {
    background: transparent;
    border: 1px solid #ffd700;
    color: #ffd700;
}

.bg-outline-gold:hover {
    background: #ffd700;
    color: #1a1a1a;
}
</style>
@endpush
