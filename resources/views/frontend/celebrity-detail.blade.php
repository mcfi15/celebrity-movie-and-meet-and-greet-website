@extends('layouts.app')

@section('title', $celebrity->name . ' - Celebrity Booking')
@section('description', 'Book ' . $celebrity->name . ' for your next event. ' . Str::limit($celebrity->bio, 150))

@section('content')
<!-- Hero Section -->
<section class="celebrity-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="celebrity-image-container">
                    @if($celebrity->image)
                        <img src="{{ Storage::url($celebrity->image) }}" 
                             alt="{{ $celebrity->name }}" 
                             class="img-fluid rounded-3 shadow-lg celebrity-main-image">
                    @else
                        <div class="bg-secondary rounded-3 d-flex align-items-center justify-content-center" style="height: 500px;">
                            <i class="fas fa-user fa-5x text-white"></i>
                        </div>
                    @endif
                    
                    <!-- Rating Badge -->
                    @if($celebrity->rating)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-gold text-light fs-5">
                                <i class="fas fa-star"></i> {{ number_format($celebrity->rating, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="ps-lg-4">
                    <h1 class="display-4 fw-bold mb-3">{{ $celebrity->name }}</h1>
                    <p class="fs-4 text-gold mb-4">{{ $celebrity->profession }}</p>
                    
                    @if($celebrity->bio)
                        <p class="lead text-not-muted mb-4">{{ $celebrity->bio }}</p>
                    @endif
                    
                    <!-- Key Stats -->
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3">
                            <div class="text-center">
                                <i class="fas fa-star text-gold fs-4"></i>
                                <div class="fw-bold">{{ number_format($celebrity->rating, 1) ?: 'N/A' }}</div>
                                <small class="text-not-muted">Rating</small>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="text-center">
                                <i class="fas fa-concierge-bell text-gold fs-4"></i>
                                <div class="fw-bold">{{ $celebrity->services->count() }}</div>
                                <small class="text-not-muted">Services</small>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="text-center">
                                <i class="fas fa-calendar-check text-gold fs-4"></i>
                                <div class="fw-bold">{{ $celebrity->bookings->count() }}</div>
                                <small class="text-not-muted">Bookings</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('booking.create') }}?celebrity={{ $celebrity->id }}" class="btn btn-gold btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i> Book {{ $celebrity->name }}
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-gold btn-lg">
                            <i class="fas fa-envelope me-2"></i> Inquire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
@if($celebrity->availableServices->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">Available Services</h2>
                <p class="lead text-not-muted">
                    Choose from {{ $celebrity->name }}'s available booking options and packages.
                </p>
            </div>
        </div>
        
        <div class="row">
            @foreach($celebrity->availableServices as $service)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card bg-card h-100 service-card">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="bg-gold rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                    @switch($service->serviceType->name)
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
                                <h5 class="fw-bold mb-2">{{ $service->serviceType->name }}</h5>
                            </div>
                            
                            <div class="text-center mb-4">
                                <div class="fs-3 fw-bold text-gold">${{ number_format($service->price) }}</div>
                                @if($service->duration)
                                    <small class="text-not-muted">{{ $service->duration }} minutes</small>
                                @endif
                            </div>
                            
                            @if($service->serviceType->description)
                                <p class="text-not-muted mb-4">{{ $service->serviceType->description }}</p>
                            @endif
                            
                            @if($service->description)
                                <div class="mb-4">
                                    <h6 class="fw-bold">Service Details</h6>
                                    <p class="text-not-muted small">{{ $service->description }}</p>
                                </div>
                            @endif
                            
                            <div class="text-center">
                                <a href="{{ route('booking.create') }}?celebrity={{ $celebrity->id }}&service={{ $service->serviceType->id }}" 
                                   class="btn btn-gold w-100">
                                    <i class="fas fa-calendar-plus me-2"></i> Book This Service
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Achievements Section -->
@if($celebrity->achievements)
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="h1 fw-bold text-center mb-5">Achievements & Recognition</h2>
                <div class="achievements-content">
                    {!! nl2br(e($celebrity->achievements)) !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Gallery Section -->
@if($celebrity->gallery && count($celebrity->gallery) > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">Photo Gallery</h2>
                <p class="lead text-not-muted">
                    Get a glimpse of {{ $celebrity->name }}'s recent events and appearances.
                </p>
            </div>
        </div>
        
        <div class="row">
            @foreach(array_slice($celebrity->gallery, 0, 6) as $index => $image)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="gallery-item">
                        <img src="{{ Storage::url($image) }}" 
                             alt="Gallery image {{ $index + 1 }}" 
                             class="img-fluid rounded-3 shadow gallery-image"
                             data-bs-toggle="modal" 
                             data-bs-target="#galleryModal{{ $index }}">
                    </div>
                    
                    <!-- Gallery Modal -->
                    <div class="modal fade" id="galleryModal{{ $index }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content bg-dark">
                                <div class="modal-body p-0">
                                    <img src="{{ Storage::url($image) }}" 
                                         alt="Gallery image {{ $index + 1 }}" 
                                         class="img-fluid w-100">
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Social Media Section -->
@if($celebrity->social_media && count($celebrity->social_media) > 0)
<section class="py-5 bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="h1 fw-bold mb-4">Connect with {{ $celebrity->name }}</h2>
                <p class="text-not-muted mb-4">Follow {{ $celebrity->name }} on social media for the latest updates.</p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    @foreach($celebrity->social_media as $platform => $url)
                        <a href="{{ $url }}" target="_blank" class="btn btn-outline-gold">
                            @switch($platform)
                                @case('instagram')
                                    <i class="fab fa-instagram me-2"></i> Instagram
                                    @break
                                @case('twitter')
                                    <i class="fab fa-twitter me-2"></i> Twitter
                                    @break
                                @case('facebook')
                                    <i class="fab fa-facebook me-2"></i> Facebook
                                    @break
                                @case('youtube')
                                    <i class="fab fa-youtube me-2"></i> YouTube
                                    @break
                                @case('tiktok')
                                    <i class="fab fa-tiktok me-2"></i> TikTok
                                    @break
                                @default
                                    <i class="fas fa-link me-2"></i> {{ ucfirst($platform) }}
                            @endswitch
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Related Celebrities -->
@if($relatedCelebrities->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="h1 fw-bold mb-4">You Might Also Like</h2>
                <p class="lead text-not-muted">
                    Other celebrities in the {{ $celebrity->profession }} category.
                </p>
            </div>
        </div>
        
        <div class="row">
            @foreach($relatedCelebrities as $related)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card bg-card h-100 celebrity-card">
                        <div class="position-relative">
                            @if($related->image)
                                <img src="{{ Storage::url($related->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $related->name }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary" style="height: 250px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                            
                            @if($related->rating)
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-gold text-light">
                                        <i class="fas fa-star"></i> {{ number_format($related->rating, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $related->name }}</h5>
                            <p class="text-gold mb-3">{{ $related->profession }}</p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('celebrity.show', $related->slug) }}" class="btn btn-outline-gold">
                                    <i class="fas fa-eye me-2"></i> View Profile
                                </a>
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
                <h2 class="h1 fw-bold mb-4">Ready to Book {{ $celebrity->name }}?</h2>
                <p class="lead text-not-muted mb-4">
                    Don't miss this opportunity to create unforgettable memories with {{ $celebrity->name }}. 
                    Book your experience today!
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('booking.create') }}?celebrity={{ $celebrity->id }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i> Book {{ $celebrity->name }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-question-circle me-2"></i> Have Questions?
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.celebrity-hero {
    padding: 100px 0;
}

.celebrity-image-container {
    position: relative;
}

.celebrity-main-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.bg-section {
    background-color: rgba(255, 255, 255, 0.02);
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

.celebrity-card {
    transition: transform 0.3s ease;
}

.celebrity-card:hover {
    transform: translateY(-3px);
}

.gallery-image {
    cursor: pointer;
    transition: transform 0.3s ease;
    height: 250px;
    width: 100%;
    object-fit: cover;
}

.gallery-image:hover {
    transform: scale(1.05);
}

.gallery-item {
    overflow: hidden;
    border-radius: 0.5rem;
}

.achievements-content {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
    padding: 2rem;
    border-left: 4px solid #ffd700;
}
</style>
@endpush
