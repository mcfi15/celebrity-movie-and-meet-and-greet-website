@extends('layouts.app')

@section('title', 'About Us - ' . $settings->site_name)
@section('description', 'Learn more about our celebrity booking agency and our mission to connect fans with their favorite stars.')

@section('content')
<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    About <span class="gradient-text">{{ $settings->site_name }}</span>
                </h1>
                <p class="lead text-not-muted mb-4">
                    {{ $settings->site_description }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold">
                        <i class="fas fa-calendar-plus me-2"></i> Start Booking
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-gold">
                        <i class="fas fa-envelope me-2"></i> Contact Us
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="About Us" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient rounded-3" style="background: linear-gradient(45deg, rgba(255,215,0,0.1), rgba(26,26,26,0.3));"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-users service-icon text-gold fs-1"></i>
                        <h3 class="text-gold mt-3">{{ $totalCelebrities }}+</h3>
                        <p class="text-not-muted">Celebrity Partners</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-concierge-bell service-icon text-gold fs-1"></i>
                        <h3 class="text-gold mt-3">{{ $totalServices }}+</h3>
                        <p class="text-not-muted">Service Types</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-award service-icon text-gold fs-1"></i>
                        <h3 class="text-gold mt-3">100%</h3>
                        <p class="text-not-muted">Client Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-bold mb-4">Our Mission</h2>
                <p class="lead text-not-muted mb-5">
                    We believe that everyone deserves the opportunity to meet their heroes and create unforgettable memories. 
                    Our platform bridges the gap between celebrities and fans, creating meaningful connections and experiences 
                    that last a lifetime.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-handshake fs-3 text-light"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Trust & Reliability</h4>
                    <p class="text-not-muted">
                        We ensure every booking is handled with professionalism and complete transparency.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-star fs-3 text-light"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Premium Experience</h4>
                    <p class="text-not-muted">
                        Every interaction is crafted to exceed expectations and create magical moments.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-heart fs-3 text-light"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Personal Connection</h4>
                    <p class="text-not-muted">
                        We facilitate genuine connections between celebrities and their fans.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team/Values Section -->
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="h1 fw-bold mb-4">Why Choose Us?</h2>
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">Verified Celebrities</h5>
                                <p class="text-not-muted small">All our celebrity partners are thoroughly verified and authentic.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">Secure Booking</h5>
                                <p class="text-not-muted small">Your payments and personal information are completely secure.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">24/7 Support</h5>
                                <p class="text-not-muted small">Our dedicated team is available round the clock to assist you.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-gold me-3 fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold">Best Prices</h5>
                                <p class="text-not-muted small">Competitive pricing with no hidden fees or charges.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="Why Choose Us" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
@if($testimonials->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">What Our Clients Say</h2>
                <p class="lead text-not-muted">
                    Don't just take our word for it. Here's what our satisfied clients have to say about their experiences.
                </p>
            </div>
        </div>
        
        <div class="row">
            @foreach($testimonials as $testimonial)
                <div class="col-md-4 mb-4">
                    <div class="card bg-card h-100">
                        <div class="card-body text-center">
                            @if($testimonial->image)
                                <img src="{{ Storage::url($testimonial->image) }}" 
                                     alt="{{ $testimonial->name }}" 
                                     class="rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fs-3 text-light"></i>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-gold' : 'text-not-muted' }}"></i>
                                @endfor
                            </div>
                            
                            <blockquote class="mb-3">
                                <p class="text-not-muted fst-italic">"{{ $testimonial->message }}"</p>
                            </blockquote>
                            
                            <div>
                                <h6 class="fw-bold mb-1">{{ $testimonial->name }}</h6>
                                @if($testimonial->position)
                                    <small class="text-not-muted">{{ $testimonial->position }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-bold mb-4">Ready to Create Memories?</h2>
                <p class="lead text-not-muted mb-4">
                    Join thousands of satisfied clients who have created unforgettable experiences with their favorite celebrities.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i> Book Your Experience
                    </a>
                    <a href="{{ route('celebrities') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-users me-2"></i> Browse Celebrities
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

.service-icon {
    font-size: 3rem;
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
</style>
@endpush
