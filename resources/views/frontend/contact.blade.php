@extends('layouts.app')

@section('title', 'Contact Us - ' . $settings->site_name)

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Get In <span class="text-gold">Touch</span></h1>
        <p class="lead text-not-muted">Have questions? We'd love to hear from you. Send us a message!</p>
    </div>
    
    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-5">
            <div class="card bg-card">
                <div class="card-header">
                    <h3 class="mb-0 text-gold">
                        <i class="fas fa-envelope me-2"></i> Send us a Message
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="{{ old('subject') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" 
                                      placeholder="Tell us about your inquiry, event requirements, or any questions you have..." 
                                      required>{{ old('message') }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-gold btn-lg">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="card bg-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 text-gold">
                        <i class="fas fa-info-circle me-2"></i> Contact Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope fa-lg text-gold"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Email</h6>
                            <a href="mailto:{{ $settings->site_email }}" class="text-not-muted text-decoration-none">
                                {{ $settings->site_email }}
                            </a>
                        </div>
                    </div>
                    
                    @if($settings->site_phone)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-phone fa-lg text-gold"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Phone</h6>
                            <a href="tel:{{ $settings->site_phone }}" class="text-not-muted text-decoration-none">
                                {{ $settings->site_phone }}
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($settings->site_address)
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt fa-lg text-gold"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Address</h6>
                            <p class="text-not-muted mb-0">{{ $settings->site_address }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-lg text-gold"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Business Hours</h6>
                            <p class="text-not-muted mb-0">
                                Mon - Fri: 9:00 AM - 6:00 PM<br>
                                Sat - Sun: 10:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card bg-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 text-gold">
                        <i class="fas fa-link me-2"></i> Quick Links
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('booking.create') }}" class="btn btn-outline-gold">
                            <i class="fas fa-calendar-plus me-2"></i> Book Celebrity
                        </a>
                        <a href="{{ route('celebrities') }}" class="btn btn-outline-gold">
                            <i class="fas fa-users me-2"></i> View Celebrities
                        </a>
                        <a href="{{ route('services') }}" class="btn btn-outline-gold">
                            <i class="fas fa-list me-2"></i> Our Services
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="card bg-card">
                <div class="card-header">
                    <h5 class="mb-0 text-gold">
                        <i class="fas fa-share-alt me-2"></i> Follow Us
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-gold btn-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-gold btn-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-gold btn-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-gold btn-sm">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Frequently Asked <span class="text-gold">Questions</span></h2>
            </div>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item bg-card border-secondary">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button bg-card text-light collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse1">
                            How far in advance should I book a celebrity?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-not-muted">
                            We recommend booking at least 2-4 weeks in advance for most events. However, for major celebrities or during peak seasons, we suggest booking 6-8 weeks ahead to ensure availability.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item bg-card border-secondary">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button bg-card text-light collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse2">
                            What is included in the booking price?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-not-muted">
                            The booking price typically includes the celebrity's appearance fee, basic meet & greet time, and standard photos. Additional services like extended sessions, special requests, or travel expenses may incur extra charges.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item bg-card border-secondary">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button bg-card text-light collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse3">
                            Can I request specific activities during the meet & greet?
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-not-muted">
                            Yes! We encourage you to share your preferences and special requests. While we cannot guarantee all requests, we work closely with celebrities to accommodate reasonable requests and make your experience memorable.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item bg-card border-secondary">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button bg-card text-light collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse4">
                            What happens if a celebrity cancels?
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-not-muted">
                            In the rare event of a cancellation, we will immediately work to find a suitable replacement or offer a full refund. We maintain professional relationships with our celebrities to minimize such occurrences.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection