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

                        @if($paymentMethods->count() > 0)
                        <!-- Payment Method -->
                        <h5 class="text-gold mb-3">Payment Method</h5>
                        <div class="mb-4">
                            @foreach($paymentMethods as $paymentMethod)
                                <div class="form-check mb-3 payment-method-option" data-method="{{ $paymentMethod->slug }}">
                                    <input class="form-check-input payment-method-radio" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_{{ $paymentMethod->slug }}" 
                                           value="{{ $paymentMethod->slug }}"
                                           data-min-amount="{{ $paymentMethod->minimum_amount ?? 0 }}"
                                           data-max-amount="{{ $paymentMethod->maximum_amount ?? 0 }}"
                                           data-fee-percentage="{{ $paymentMethod->processing_fee_percentage ?? 0 }}"
                                           data-fee-fixed="{{ $paymentMethod->processing_fee_fixed ?? 0 }}"
                                           data-instructions="{{ $paymentMethod->instructions ?? '' }}">
                                    <label class="form-check-label w-100" for="payment_{{ $paymentMethod->slug }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                {!! $paymentMethod->icon_html !!}
                                                <span class="ms-2 fw-bold">{{ $paymentMethod->name }}</span>
                                                @if($paymentMethod->processing_fee_percentage > 0 || $paymentMethod->processing_fee_fixed > 0)
                                                    <small class="text-muted ms-2">(Fee: {{ $paymentMethod->formatted_processing_fee }})</small>
                                                @endif
                                            </div>
                                            @if($paymentMethod->minimum_amount || $paymentMethod->maximum_amount)
                                                <small class="text-muted">{{ $paymentMethod->amount_limits_text }}</small>
                                            @endif
                                        </div>
                                        @if($paymentMethod->description)
                                            <div class="mt-1">
                                                <small class="text-muted">{{ $paymentMethod->description }}</small>
                                            </div>
                                        @endif
                                    </label>
                                    @if($paymentMethod->instructions)
                                        <div class="mt-2 ms-4 payment-instructions" id="instructions_{{ $paymentMethod->slug }}" style="display: none;">
                                            <div class="alert alert-info py-2 mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Payment Instructions:</strong><br>
                                                {{ $paymentMethod->instructions }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input payment-method-radio" type="radio" name="payment_method" id="payment_later" value="" checked>
                                <label class="form-check-label" for="payment_later">
                                    <i class="fas fa-clock me-2 text-muted"></i> Pay Later (After Approval)
                                    <br><small class="text-muted">Complete payment after your booking is approved by our team</small>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Payment Summary -->
                        <div id="payment-summary" class="card mt-3" style="display: none;">
                            <div class="card-header bg-gold text-dark">
                                <h6 class="mb-0"><i class="fas fa-receipt me-2"></i>Payment Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">Booking Amount:</div>
                                    <div class="col-4 text-end" id="summary-base-amount">$0.00</div>
                                </div>
                                <div class="row" id="payment-fee-row" style="display: none;">
                                    <div class="col-8">Processing Fee:</div>
                                    <div class="col-4 text-end" id="summary-fee-amount">$0.00</div>
                                </div>
                                <hr>
                                <div class="row fw-bold">
                                    <div class="col-8">Total Amount:</div>
                                    <div class="col-4 text-end text-success" id="summary-total-amount">$0.00</div>
                                </div>
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
                    
                    // Update payment summary if a payment method is selected
                    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (selectedPaymentMethod && selectedPaymentMethod.value) {
                        updatePaymentSummary(selectedPaymentMethod);
                    }
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
    
    // Payment method handling
    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');
    const paymentSummary = document.getElementById('payment-summary');
    
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            handlePaymentMethodChange(this);
        });
    });
    
    function handlePaymentMethodChange(selectedRadio) {
        // Hide all payment instructions
        document.querySelectorAll('.payment-instructions').forEach(el => {
            el.style.display = 'none';
        });
        
        if (selectedRadio.value && selectedRadio.value !== '') {
            // Show instructions for selected payment method
            const instructionsEl = document.getElementById('instructions_' + selectedRadio.value);
            if (instructionsEl) {
                instructionsEl.style.display = 'block';
            }
            
            // Show payment summary
            updatePaymentSummary(selectedRadio);
            paymentSummary.style.display = 'block';
        } else {
            // Hide payment summary for "pay later"
            paymentSummary.style.display = 'none';
        }
    }
    
    function updatePaymentSummary(paymentMethodRadio) {
        const totalPriceText = totalPriceSpan ? totalPriceSpan.textContent : '$0';
        const baseAmount = parseFloat(totalPriceText.replace(/[$,]/g, '')) || 0;
        
        const feePercentage = parseFloat(paymentMethodRadio.dataset.feePercentage) || 0;
        const feeFixed = parseFloat(paymentMethodRadio.dataset.feeFixed) || 0;
        
        const feeAmount = (baseAmount * feePercentage / 100) + feeFixed;
        const totalAmount = baseAmount + feeAmount;
        
        document.getElementById('summary-base-amount').textContent = '$' + baseAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
        
        const feeRow = document.getElementById('payment-fee-row');
        if (feeAmount > 0) {
            document.getElementById('summary-fee-amount').textContent = '$' + feeAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
            feeRow.style.display = 'flex';
        } else {
            feeRow.style.display = 'none';
        }
        
        document.getElementById('summary-total-amount').textContent = '$' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
        
        // Validate amount limits
        validatePaymentLimits(paymentMethodRadio, totalAmount);
    }
    
    function validatePaymentLimits(paymentMethodRadio, amount) {
        const minAmount = parseFloat(paymentMethodRadio.dataset.minAmount) || 0;
        const maxAmount = parseFloat(paymentMethodRadio.dataset.maxAmount) || 0;
        
        const submitBtn = document.getElementById('submit-btn');
        const methodName = paymentMethodRadio.parentElement.querySelector('label .fw-bold').textContent;
        
        if (minAmount > 0 && amount < minAmount) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Amount below ${methodName} minimum ($${minAmount.toLocaleString()})`;
            submitBtn.classList.add('btn-warning');
            submitBtn.classList.remove('btn-gold');
        } else if (maxAmount > 0 && amount > maxAmount) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Amount exceeds ${methodName} maximum ($${maxAmount.toLocaleString()})`;
            submitBtn.classList.add('btn-warning');
            submitBtn.classList.remove('btn-gold');
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Booking Request';
            submitBtn.classList.remove('btn-warning');
            submitBtn.classList.add('btn-gold');
        }
    }
    
    // Form submission handling
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (selectedPaymentMethod && selectedPaymentMethod.value) {
            // Show processing message for payment methods
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing Payment...';
        } else {
            // Show booking message for pay later
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating Booking...';
        }
        
        submitBtn.disabled = true;
    });
});
</script>
@endpush
@endsection