@extends('layouts.app')

@section('title', 'Booking Confirmation - ' . $settings->site_name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Header -->
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold text-gold mb-3">Booking Confirmed!</h1>
                <p class="lead text-unmute">
                    Thank you for your booking request. We've received your information and will contact you shortly.
                </p>
            </div>

            <!-- Booking Details Card -->
            <div class="card bg-card mb-4">
                <div class="card-header">
                    <h3 class="mb-0 text-gold">
                        <i class="fas fa-receipt me-2"></i> Booking Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Booking Reference -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-gold">Booking Reference</label>
                            <p class="h5 text-white">#{{ $booking->booking_number }}</p>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-gold">Status</label>
                            <p>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i> {{ ucfirst($booking->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <!-- Celebrity Information -->
                    <h5 class="text-gold mb-3">
                        <i class="fas fa-star me-2"></i> Celebrity & Service
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Celebrity</label>
                            <p class="text-white">{{ $booking->celebrity->name }}</p>
                            <small class="text-unmute">{{ $booking->celebrity->profession }}</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Service Type</label>
                            <p class="text-white">{{ $booking->serviceType->name }}</p>
                            @if($booking->duration_hours)
                                <small class="text-unmute">Duration: {{ $booking->duration_hours }} hours</small>
                            @endif
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <!-- Customer Information -->
                    <h5 class="text-gold mb-3">
                        <i class="fas fa-user me-2"></i> Customer Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <p class="text-white">{{ $booking->customer_name }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="text-white">{{ $booking->customer_email }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="text-white">{{ $booking->customer_phone }}</p>
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <!-- Event Details -->
                    <h5 class="text-gold mb-3">
                        <i class="fas fa-calendar-alt me-2"></i> Event Details
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Event Date</label>
                            <p class="text-white">{{ $booking->event_date->format('F j, Y') }}</p>
                        </div>
                        
                        @if($booking->event_time)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Event Time</label>
                            <p class="text-white">{{ $booking->event_time->format('g:i A') }}</p>
                        </div>
                        @endif
                        
                        @if($booking->event_location)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Location</label>
                            <p class="text-white">{{ $booking->event_location }}</p>
                        </div>
                        @endif
                        
                        @if($booking->event_description)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Event Description</label>
                            <p class="text-white">{{ $booking->event_description }}</p>
                        </div>
                        @endif
                        
                        @if($booking->special_requests)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Special Requests</label>
                            <p class="text-white">{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>

                    <hr class="border-secondary">

                    <!-- Pricing Information -->
                    <h5 class="text-gold mb-3">
                        <i class="fas fa-dollar-sign me-2"></i> Pricing
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Base Price</label>
                            <p class="text-white">${{ number_format($booking->base_price, 2) }}</p>
                        </div>
                        
                        @if($booking->additional_charges > 0)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Additional Charges</label>
                            <p class="text-white">${{ number_format($booking->additional_charges, 2) }}</p>
                        </div>
                        @endif
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-gold">Total Amount</label>
                            <p class="h4 text-gold">${{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps Card -->
            <div class="card bg-card mb-4">
                <div class="card-header">
                    <h3 class="mb-0 text-gold">
                        <i class="fas fa-info-circle me-2"></i> Next Steps
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="next-step">
                                <i class="fas fa-envelope fa-2x text-gold mb-3"></i>
                                <h6 class="text-white">1. Email Confirmation</h6>
                                <p class="text-unmute small">You'll receive a confirmation email within 24 hours</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center mb-3">
                            <div class="next-step">
                                <i class="fas fa-phone fa-2x text-gold mb-3"></i>
                                <h6 class="text-white">2. Personal Contact</h6>
                                <p class="text-unmute small">Our team will call you to discuss details</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center mb-3">
                            <div class="next-step">
                                <i class="fas fa-handshake fa-2x text-gold mb-3"></i>
                                <h6 class="text-white">3. Finalize Booking</h6>
                                <p class="text-unmute small">Complete payment and contract details</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card bg-card">
                <div class="card-body text-center">
                    <h5 class="text-gold mb-3">
                        <i class="fas fa-question-circle me-2"></i> Questions?
                    </h5>
                    <p class="text-unmute mb-3">
                        If you have any questions about your booking, please don't hesitate to contact us.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('contact') }}" class="btn btn-outline-gold">
                            <i class="fas fa-envelope me-2"></i> Contact Us
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-gold">
                            <i class="fas fa-home me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .success-icon {
        animation: checkmark 0.6s ease-in-out;
    }
    
    @keyframes checkmark {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .next-step {
        transition: transform 0.3s ease;
    }
    
    .next-step:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
