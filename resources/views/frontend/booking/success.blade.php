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

            <!-- Crypto Payment Section -->
            @if($booking->cryptoWallet)
                @php
                    $wallet = $booking->cryptoWallet;
                    $isAwaitingProof = $booking->status === 'pending' && $booking->payment_status !== 'paid';
                    $isUnderVerification = $booking->status === 'pending_payment_verification';
                    $isPaid = $booking->payment_status === 'paid';
                    $isRejected = $booking->status === 'rejected' || $booking->payment_status === 'failed';
                @endphp

                <div class="card bg-card mb-4" id="crypto-payment-panel">
                    <div class="card-header">
                        <h3 class="mb-0 text-gold">
                            <i class="fas fa-wallet me-2"></i> Complete Your Crypto Payment
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($isPaid)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Payment confirmed. Thank you for your booking!
                            </div>
                        @elseif($isUnderVerification)
                            <div class="alert alert-info">
                                <i class="fas fa-hourglass-half me-2"></i>
                                Your payment proof has been submitted and is awaiting verification by our team.
                                @if($booking->payment_tx_hash)
                                    <br><small>Transaction Hash: <code>{{ $booking->payment_tx_hash }}</code></small>
                                @endif
                            </div>
                        @elseif($isRejected)
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i>
                                Unfortunately your payment could not be verified. Please contact our team for assistance.
                            </div>
                        @endif

                        @if($isAwaitingProof || $isUnderVerification)
                            <!-- Wallet Details -->
                            <div class="row">
                                <div class="col-md-5 text-center mb-4">
                                    @if($wallet->wallet_image)
                                        <img src="{{ $wallet->wallet_image_url }}" alt="{{ $wallet->name }}"
                                             class="img-fluid mb-3" style="max-height: 70px; object-fit: contain;">
                                    @endif
                                    <h5 class="text-white">{{ $wallet->name }}</h5>
                                    @if($wallet->qr_code_image)
                                        <div class="mb-2">
                                            <img src="{{ $wallet->qr_code_image_url }}" alt="{{ $wallet->name }} QR code"
                                                 class="rounded bg-white p-2" style="max-width: 160px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-gold">Exact Amount to Send</label>
                                        <h3 class="text-gold">${{ number_format($booking->total_amount, 2) }}</h3>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-gold">Deposit Wallet Address</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <code id="wallet-address-display" class="text-white p-2 rounded bg-secondary"
                                                  style="word-break: break-all; flex: 1;">{{ $wallet->wallet_address }}</code>
                                            <button type="button" class="btn btn-gold btn-sm flex-shrink-0"
                                                    data-copy-address="{{ $wallet->wallet_address }}"
                                                    data-copy-label='<i class="fas fa-check"></i> Copied!'>
                                                <i class="fas fa-copy me-1"></i> Copy Address
                                            </button>
                                        </div>
                                    </div>
                                    @if($wallet->instructions)
                                        <div class="alert alert-info py-2 mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Instructions:</strong> {{ $wallet->instructions }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($isAwaitingProof)
                            <hr class="border-secondary">
                            <h5 class="text-gold mb-3">
                                <i class="fas fa-paper-plane me-2"></i> Submit Payment Proof
                            </h5>
                            <p class="text-unmute small mb-3">
                                After sending the exact amount, provide your transaction hash (TXID) below so our team can
                                verify your payment. A receipt/screenshot is optional but recommended.
                            </p>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('booking.payment.submit', $booking) }}" method="POST" enctype="multipart/form-data" id="proof-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Booking Email *</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email"
                                           value="{{ $booking->customer_email }}" required>
                                    <div class="form-text text-unmute">Used to confirm this proof belongs to your booking.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_tx_hash" class="form-label">Transaction Hash / TXID *</label>
                                    <input type="text" class="form-control" id="payment_tx_hash" name="payment_tx_hash"
                                           placeholder="e.g., 0x4f8a... or bc1q..."
                                           value="{{ old('payment_tx_hash') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_proof_image" class="form-label">Receipt / Proof Image</label>
                                    <input type="file" class="form-control" id="payment_proof_image" name="payment_proof_image"
                                           accept="image/png,image/jpeg,image/webp">
                                    <div class="form-text text-unmute">PNG, JPEG, WEBP. Max 2MB.</div>
                                </div>
                                <button type="submit" class="btn btn-gold w-100 py-2" id="proof-submit-btn">
                                    <i class="fas fa-check me-2"></i> Submit Payment Proof
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- JS Copy feedback styles handled globally -->
            @endif

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

    .copy-toast {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        background: var(--secondary-color);
        color: var(--text-light);
        border-left: 4px solid #28a745;
        border-radius: 8px;
        padding: 16px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        font-size: 0.9rem;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        max-width: 340px;
    }

    .copy-toast-error {
        border-left-color: #dc3545;
    }

    .copy-toast-show {
        opacity: 1;
        transform: translateY(0);
    }

    .copy-success {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/copy-to-clipboard.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.CopyClipboard) {
        window.CopyClipboard.init();
    }

    var proofForm = document.getElementById('proof-form');
    if (proofForm) {
        proofForm.addEventListener('submit', function() {
            var btn = document.getElementById('proof-submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
        });
    }
});
</script>
@endpush

