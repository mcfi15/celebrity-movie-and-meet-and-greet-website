@extends('layouts.app')

@section('title', 'Book Celebrity - ' . $settings->site_name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-card">
                <div class="card-header">
                    <h2 class="mb-0 text-gold">
                        <i class="fas fa-calendar-plus me-2"></i> Book Celebrity Experience
                    </h2>
                    <p class="text-muted mb-0">Fill out the form below to request a celebrity booking</p>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('booking.store') }}" method="POST" id="booking-form">
                        @csrf
                        
                        <!-- Celebrity & Service Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="celebrity_id" class="form-label text-gold">Select Celebrity *</label>
                                <select class="form-select" id="celebrity_id" name="celebrity_id" required>
                                    <option value="">Choose a celebrity...</option>
                                    @foreach($celebrities as $celebrity)
                                        <option value="{{ $celebrity->id }}" 
                                                {{ (old('celebrity_id') == $celebrity->id || ($selectedCelebrity && $selectedCelebrity->id == $celebrity->id)) ? 'selected' : '' }}
                                                data-price="{{ $celebrity->base_price }}">
                                            {{ $celebrity->name }} - {{ $celebrity->profession }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="service_type_id" class="form-label text-gold">Select Service *</label>
                                <select class="form-select" id="service_type_id" name="service_type_id" required>
                                    <option value="">Choose a service...</option>
                                    @foreach($serviceTypes as $service)
                                        <option value="{{ $service->id }}" 
                                                {{ (old('service_type_id') == $service->id || ($selectedService && $selectedService->id == $service->id)) ? 'selected' : '' }}
                                                data-price="{{ $service->base_price }}">
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Price Display -->
                        <div class="alert alert-info" id="price-display" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Base Price per Hour:</span>
                                <span class="fw-bold text-gold" id="base-price">$0</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Duration:</span>
                                <span id="duration-display">1 hour(s)</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Estimated Cost:</span>
                                <span class="fw-bold text-gold fs-5" id="total-price">$0</span>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <h5 class="text-gold mb-3">Customer Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                       value="{{ old('customer_email', Auth::user()->email ?? '') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                                   value="{{ old('customer_phone', Auth::user()->phone ?? '') }}" required>
                        </div>

                        <!-- Event Details -->
                        <h5 class="text-gold mb-3">Event Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="datetime-local" class="form-control" id="event_date" name="event_date" 
                                       value="{{ old('event_date') }}" min="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="duration_hours" class="form-label">Duration (Hours) *</label>
                                <select class="form-select" id="duration_hours" name="duration_hours" required>
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('duration_hours', 1) == $i ? 'selected' : '' }}>
                                            {{ $i }} hour{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="event_location" class="form-label">Event Location</label>
                            <input type="text" class="form-control" id="event_location" name="event_location" 
                                   value="{{ old('event_location') }}" placeholder="e.g., Los Angeles, CA">
                        </div>
                        
                        <div class="mb-3">
                            <label for="event_details" class="form-label">Event Details</label>
                            <textarea class="form-control" id="event_details" name="event_details" rows="3" 
                                      placeholder="Please describe your event, special requirements, or any additional information...">{{ old('event_details') }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="customer_message" class="form-label">Additional Message</label>
                            <textarea class="form-control" id="customer_message" name="customer_message" rows="3" 
                                      placeholder="Any special requests or questions for the celebrity...">{{ old('customer_message') }}</textarea>
                        </div>

                        @if($settings->payment_enabled && $settings->payment_methods)
                        <!-- Payment Method -->
                        <h5 class="text-gold mb-3">Payment Method</h5>
                        <div class="mb-4">
                            @if(in_array('stripe', $settings->payment_methods))
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_stripe" value="stripe">
                                    <label class="form-check-label" for="payment_stripe">
                                        <i class="fab fa-cc-stripe me-2 text-primary"></i> Credit/Debit Card (Stripe)
                                    </label>
                                </div>
                            @endif
                            
                            @if(in_array('crypto', $settings->payment_methods))
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_crypto" value="crypto">
                                    <label class="form-check-label" for="payment_crypto">
                                        <i class="fab fa-bitcoin me-2 text-warning"></i> Cryptocurrency
                                    </label>
                                </div>
                            @endif
                            
                            @if(in_array('bank_transfer', $settings->payment_methods))
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="bank_transfer">
                                    <label class="form-check-label" for="payment_bank">
                                        <i class="fas fa-university me-2 text-info"></i> Bank Transfer
                                    </label>
                                </div>
                            @endif
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_later" value="" checked>
                                <label class="form-check-label" for="payment_later">
                                    <i class="fas fa-clock me-2 text-muted"></i> Pay Later (After Approval)
                                </label>
                            </div>
                        </div>
                        @endif

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-gold">Terms and Conditions</a> and 
                                    <a href="#" class="text-gold">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('home') }}" class="btn btn-outline-gold">
                                <i class="fas fa-arrow-left me-2"></i> Back to Home
                            </a>
                            <button type="submit" class="btn btn-gold" id="submit-btn">
                                <i class="fas fa-paper-plane me-2"></i> Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const celebritySelect = document.getElementById('celebrity_id');
    const serviceSelect = document.getElementById('service_type_id');
    const durationSelect = document.getElementById('duration_hours');
    const priceDisplay = document.getElementById('price-display');
    const basePriceSpan = document.getElementById('base-price');
    const totalPriceSpan = document.getElementById('total-price');
    const durationDisplay = document.getElementById('duration-display');
    
    function updatePrice() {
        const celebrityId = celebritySelect.value;
        const serviceId = serviceSelect.value;
        const duration = parseInt(durationSelect.value) || 1;
        
        if (celebrityId && serviceId) {
            // Show loading
            priceDisplay.style.display = 'block';
            basePriceSpan.textContent = 'Loading...';
            totalPriceSpan.textContent = 'Loading...';
            
            fetch(`{{ route('api.service-price') }}?celebrity_id=${celebrityId}&service_type_id=${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    const basePrice = parseFloat(data.price);
                    const totalPrice = basePrice * duration;
                    
                    basePriceSpan.textContent = `$${basePrice.toLocaleString()}`;
                    totalPriceSpan.textContent = `$${totalPrice.toLocaleString()}`;
                    durationDisplay.textContent = `${duration} hour${duration > 1 ? 's' : ''}`;
                })
                .catch(error => {
                    console.error('Error fetching price:', error);
                    basePriceSpan.textContent = 'Error';
                    totalPriceSpan.textContent = 'Error';
                });
        } else {
            priceDisplay.style.display = 'none';
        }
    }
    
    celebritySelect.addEventListener('change', updatePrice);
    serviceSelect.addEventListener('change', updatePrice);
    durationSelect.addEventListener('change', updatePrice);
    
    // Initial price update if values are pre-selected
    updatePrice();
    
    // Form submission handling
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
        submitBtn.disabled = true;
    });
});
</script>
@endpush
@endsection