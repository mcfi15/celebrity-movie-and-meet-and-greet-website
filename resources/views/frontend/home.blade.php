@extends('layouts.app')

@section('title', 'Home - ' . $settings->site_name)
@section('description', $settings->site_description)

@section('content')
<!-- Hero Slider Section -->
@if($sliders->count() > 0)
<section class="hero-slider-section">
    <div class="swiper hero-slider">
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
            <div class="swiper-slide">
                <div class="slider-item position-relative">
                    <img src="{{ asset($slider->image_path) }}" alt="{{ $slider->title }}" class="slider-bg">
                    <div class="slider-overlay"></div>
                    <div class="container position-relative">
                        <div class="row align-items-center min-vh-100">
                            <div class="col-lg-8 col-xl-6">
                                <div class="slider-content text-white">
                                    <h1 class="display-3 fw-bold mb-4 slider-title" data-aos="fade-up">
                                        {{ $slider->title }}
                                    </h1>
                                    @if($slider->subtitle)
                                        <h2 class="h3 mb-4 text-gold slider-subtitle" data-aos="fade-up" data-aos-delay="200">
                                            {{ $slider->subtitle }}
                                        </h2>
                                    @endif
                                    @if($slider->description)
                                        <p class="lead mb-5 slider-description" data-aos="fade-up" data-aos-delay="400">
                                            {{ $slider->description }}
                                        </p>
                                    @endif
                                    @if($slider->hasCta())
                                        <div class="slider-cta" data-aos="fade-up" data-aos-delay="600">
                                            <a href="{{ $slider->formatted_cta_link }}" class="btn btn-gold btn-lg me-3">
                                                {{ $slider->cta_text }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Slider Navigation -->
        @if($sliders->count() > 1)
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        @endif
    </div>
</section>
@else
<!-- Fallback Hero Section if no sliders -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Book <span class="gradient-text">Celebrity Experiences</span> 
                    <br>Like Never Before
                </h1>
                <p class="lead text-not-muted mb-4">
                    Connect with your favorite celebrities for exclusive meet & greets, 
                    autograph sessions, corporate events, and unforgettable experiences.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('booking.create') }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i> Book Now
                    </a>
                    <a href="{{ route('celebrities') }}" class="btn btn-outline-gold btn-lg">
                        <i class="fas fa-users me-2"></i> View Celebrities
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Celebrity Event" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient rounded-3" style="background: linear-gradient(45deg, rgba(255,215,0,0.1), rgba(26,26,26,0.3));"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-users service-icon"></i>
                        <h3 class="text-gold">{{ $featuredCelebrities->count() }}+</h3>
                        <p class="text-not-muted">A-List Celebrities</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-star service-icon"></i>
                        <h3 class="text-gold">{{ $popularServices->count() }}+</h3>
                        <p class="text-not-muted">Service Types</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-calendar-check service-icon"></i>
                        <h3 class="text-gold">1000+</h3>
                        <p class="text-not-muted">Events Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-card h-100">
                    <div class="card-body">
                        <i class="fas fa-smile service-icon"></i>
                        <h3 class="text-gold">98%</h3>
                        <p class="text-not-muted">Client Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Celebrities -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Featured <span class="text-gold">Celebrities</span></h2>
            <p class="lead text-not-muted">Meet our exclusive roster of A-list celebrities</p>
        </div>
        
        <div class="row">
            @foreach($featuredCelebrities as $celebrity)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card celebrity-card bg-card h-100">
                    @if($celebrity->image)
                        <img src="{{ Storage::url($celebrity->image) }}" 
                             class="card-img-top celebrity-img" 
                             alt="{{ $celebrity->name }}">
                    @else
                        <div class="card-img-top celebrity-img d-flex align-items-center justify-content-center bg-secondary">
                            <i class="fas fa-user fa-4x text-not-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-gold">{{ $celebrity->name }}</h5>
                        <p class="text-not-muted small mb-2">{{ $celebrity->profession }}</p>
                        <p class="card-text text-not-muted flex-grow-1">
                            {{ Str::limit($celebrity->bio, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $celebrity->rating ? 'text-warning' : 'text-not-muted' }}"></i>
                                @endfor
                            </div>
                            <span class="text-gold fw-bold">${{ number_format($celebrity->base_price) }}+</span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('celebrity.show', $celebrity->slug) }}" class="btn btn-outline-gold w-100">
                                <i class="fas fa-eye me-2"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('celebrities') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-users me-2"></i> View All Celebrities
            </a>
        </div>
    </div>
</section>

<!-- Popular Services -->
<section class="py-5 bg-card">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Our <span class="text-gold">Services</span></h2>
            <p class="lead text-not-muted">From intimate meet & greets to grand corporate events</p>
        </div>
        
        <div class="row">
            @foreach($popularServices as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark-custom h-100 text-center">
                    <div class="card-body">
                        @if($service->icon)
                            <i class="{{ $service->icon }} service-icon"></i>
                        @else
                            <i class="fas fa-star service-icon"></i>
                        @endif
                        <h5 class="card-title text-gold">{{ $service->name }}</h5>
                        <p class="card-text text-not-muted">{{ $service->description }}</p>
                        <div class="mt-3">
                            <span class="text-gold fw-bold">${{ number_format($service->base_price) }}+</span>
                            @if($service->duration_minutes)
                                <small class="text-not-muted d-block">{{ $service->duration_minutes }} minutes</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('services') }}" class="btn btn-outline-gold btn-lg">
                <i class="fas fa-list me-2"></i> View All Services
            </a>
        </div>
    </div>
</section>

<!-- Testimonials -->
@if($testimonials->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">What Our <span class="text-gold">Clients Say</span></h2>
            <p class="lead text-not-muted">Real experiences from satisfied customers</p>
        </div>
        
        <div class="row">
            @foreach($testimonials as $testimonial)
            <div class="col-lg-4 mb-4">
                <div class="card testimonial-card h-100">
                    <div class="card-body text-center">
                        @if($testimonial->image)
                            <img src="{{ Storage::url($testimonial->image) }}" 
                                 class="rounded-circle mb-3" 
                                 width="80" height="80" 
                                 alt="{{ $testimonial->name }}">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-not-muted"></i>
                            </div>
                        @endif
                        
                        <div class="rating mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-not-muted' }}"></i>
                            @endfor
                        </div>
                        
                        <blockquote class="mb-3">
                            <p class="text-not-muted fst-italic">"{{ $testimonial->message }}"</p>
                        </blockquote>
                        
                        <div>
                            <h6 class="text-gold mb-0">{{ $testimonial->name }}</h6>
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
<section class="py-5 bg-card">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-3">Ready to Book Your <span class="text-gold">Celebrity Experience</span>?</h2>
        <p class="lead text-not-muted mb-4">Join thousands of satisfied customers who have created unforgettable memories</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('booking.create') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-calendar-plus me-2"></i> Book Now
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-gold btn-lg">
                <i class="fas fa-envelope me-2"></i> Contact Us
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<!-- AOS Animation CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    /* Hero Slider Styles */
    .hero-slider-section {
        position: relative;
        height: 100vh;
        overflow: hidden;
    }
    
    .hero-slider {
        width: 100%;
        height: 100%;
    }
    
    .slider-item {
        height: 100vh;
        overflow: hidden;
    }
    
    .slider-bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }
    
    .slider-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            45deg,
            rgba(26, 26, 26, 0.7) 0%,
            rgba(26, 26, 26, 0.4) 50%,
            rgba(255, 215, 0, 0.1) 100%
        );
        z-index: 2;
    }
    
    .slider-content {
        position: relative;
        z-index: 3;
        padding: 2rem 0;
    }
    
    .slider-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        line-height: 1.2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        margin-bottom: 1.5rem;
    }
    
    .slider-subtitle {
        color: var(--gold-color, #ffd700);
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        margin-bottom: 1.5rem;
    }
    
    .slider-description {
        font-size: clamp(1rem, 2vw, 1.25rem);
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        margin-bottom: 2rem;
        max-width: 600px;
    }
    
    /* Swiper Navigation Styles */
    .swiper-pagination {
        bottom: 30px !important;
        z-index: 10;
    }
    
    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 1;
        transition: all 0.3s ease;
    }
    
    .swiper-pagination-bullet-active {
        background: var(--gold-color, #ffd700);
        transform: scale(1.2);
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }
    
    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        color: var(--gold-color, #ffd700);
        transform: scale(1.1);
    }
    
    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 24px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-slider-section,
        .slider-item {
            height: 70vh;
            min-height: 600px;
        }
        
        .slider-content {
            text-align: center;
            padding: 1rem 0;
        }
        
        .swiper-button-next,
        .swiper-button-prev {
            display: none;
        }
        
        .swiper-pagination {
            bottom: 20px !important;
        }
    }
    
    @media (max-width: 576px) {
        .hero-slider-section,
        .slider-item {
            height: 60vh;
            min-height: 500px;
        }
        
        .slider-content {
            padding: 0.5rem 0;
        }
        
        .slider-title {
            margin-bottom: 1rem;
        }
        
        .slider-subtitle {
            margin-bottom: 1rem;
        }
        
        .slider-description {
            margin-bottom: 1.5rem;
        }
    }
    
    /* Button Styles Enhancement */
    .btn-gold {
        background: linear-gradient(135deg, #ffd700, #ffed4a);
        border: none;
        color: #1a1a1a;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }
    
    .btn-gold:hover {
        background: linear-gradient(135deg, #ffed4a, #ffd700);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        color: #1a1a1a;
    }
    
    /* Animation Enhancements */
    .slider-content [data-aos] {
        transition-duration: 1s;
    }
    
    /* Loading Animation */
    .hero-slider .swiper-slide:not(.swiper-slide-active) .slider-content [data-aos] {
        opacity: 0;
        transform: translateY(30px);
    }
    
    .hero-slider .swiper-slide-active .slider-content [data-aos] {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: false,
        mirror: true
    });
    
    // Initialize Swiper
    const heroSlider = new Swiper('.hero-slider', {
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        on: {
            slideChange: function () {
                // Refresh AOS animations on slide change
                setTimeout(() => {
                    AOS.refresh();
                }, 100);
            },
            slideChangeTransitionStart: function() {
                // Hide current slide content
                const currentSlide = document.querySelector('.swiper-slide-active');
                if (currentSlide) {
                    const content = currentSlide.querySelectorAll('[data-aos]');
                    content.forEach(el => {
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(30px)';
                    });
                }
            },
            slideChangeTransitionEnd: function() {
                // Show new slide content with animation
                const activeSlide = document.querySelector('.swiper-slide-active');
                if (activeSlide) {
                    const content = activeSlide.querySelectorAll('[data-aos]');
                    content.forEach((el, index) => {
                        setTimeout(() => {
                            el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                        }, index * 200);
                    });
                }
            }
        }
    });
    
    // Pause autoplay on hover
    const sliderContainer = document.querySelector('.hero-slider-section');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', () => {
            heroSlider.autoplay.stop();
        });
        
        sliderContainer.addEventListener('mouseleave', () => {
            heroSlider.autoplay.start();
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            heroSlider.slidePrev();
        } else if (e.key === 'ArrowRight') {
            heroSlider.slideNext();
        }
    });
    
    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    sliderContainer?.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    sliderContainer?.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                heroSlider.slideNext();
            } else {
                heroSlider.slidePrev();
            }
        }
    }
});
</script>
@endpush