@extends('layouts.admin')

@section('title', 'Booking Details')
@section('page-title', 'Booking #' . $booking->id)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Edit Booking
        </a>
        @if($booking->status === 'pending')
            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="btn btn-success" 
                        onclick="return confirm('Are you sure you want to approve this booking?')">
                    <i class="fas fa-check me-2"></i>
                    Approve
                </button>
            </form>
            <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="btn btn-danger" 
                        onclick="return confirm('Are you sure you want to reject this booking?')">
                    <i class="fas fa-times me-2"></i>
                    Reject
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Booking Status -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1">
                            @if($booking->status === 'pending')
                                <span class="badge bg-warning fs-4">Pending Review</span>
                            @elseif($booking->status === 'approved')
                                <span class="badge bg-success fs-4">Approved</span>
                            @else
                                <span class="badge bg-danger fs-4">Rejected</span>
                            @endif
                        </h2>
                        <p class="text-muted">Booking Status</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1">
                            @if($booking->payment_required)
                                @if($booking->payment_status === 'paid')
                                    <span class="badge bg-success fs-4">Paid</span>
                                @elseif($booking->payment_status === 'pending')
                                    <span class="badge bg-warning fs-4">Pending</span>
                                @else
                                    <span class="badge bg-danger fs-4">Failed</span>
                                @endif
                            @else
                                <span class="badge bg-secondary fs-4">Not Required</span>
                            @endif
                        </h2>
                        <p class="text-muted">Payment Status</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-warning">${{ number_format($booking->total_amount ?? 0, 2) }}</h2>
                        <p class="text-muted">Total Amount</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-info">{{ $booking->created_at->diffForHumans() }}</h2>
                        <p class="text-muted">Submitted</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Customer Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-white"><strong>Name:</strong></div>
                    <div class="col-sm-8 text-white">{{ $booking->customer_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-white"><strong>Email:</strong></div>
                    <div class="col-sm-8 text-white">
                        <a href="mailto:{{ $booking->customer_email }}" class="text-warning">
                            {{ $booking->customer_email }}
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-white"><strong>Phone:</strong></div>
                    <div class="col-sm-8 text-white">
                        <a href="tel:{{ $booking->customer_phone }}" class="text-warning">
                            {{ $booking->customer_phone }}
                        </a>
                    </div>
                </div>
                @if($booking->customer_address)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Address:</strong></div>
                        <div class="col-sm-8 text-white">{{ $booking->customer_address }}</div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Event Details -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Event Details
                </h5>
            </div>
            <div class="card-body">
                @if($booking->event_date)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Date:</strong></div>
                        <div class="col-sm-8 text-white">{{ \Carbon\Carbon::parse($booking->event_date)->format('l, F d, Y') }}</div>
                    </div>
                @endif
                @if($booking->event_time)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Time:</strong></div>
                        <div class="col-sm-8 text-white">{{ \Carbon\Carbon::parse($booking->event_time)->format('h:i A') }}</div>
                    </div>
                @endif
                @if($booking->duration_hours)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Duration:</strong></div>
                        <div class="col-sm-8 text-white">{{ $booking->duration_hours }} hour(s)</div>
                    </div>
                @endif
                @if($booking->event_location)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Location:</strong></div>
                        <div class="col-sm-8 text-white">{{ $booking->event_location }}</div>
                    </div>
                @endif
                @if($booking->special_requests)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Special Requests:</strong></div>
                        <div class="col-sm-8 text-white">{{ $booking->special_requests }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Celebrity & Service Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>
                    Celebrity & Service
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    @if($booking->celebrity->image)
                        <img src="{{ asset('storage/' . $booking->celebrity->image) }}" 
                             alt="{{ $booking->celebrity->name }}" 
                             class="rounded-circle me-3" 
                             width="80" 
                             height="80" 
                             style="object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="mb-1 text-white">{{ $booking->celebrity->name }}</h4>
                        <p class="text-muted mb-1">{{ $booking->celebrity->profession }}</p>
                        <span class="badge bg-primary">{{ $booking->celebrity->category }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-white"><strong>Service:</strong></div>
                    <div class="col-sm-8 text-white">
                        <span class="badge bg-secondary fs-6">{{ $booking->serviceType->name }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4 text-white"><strong>Hourly Rate:</strong></div>
                    <div class="col-sm-8 text-white">${{ number_format($booking->celebrity->hourly_rate, 2) }}/hour</div>
                </div>
                
                @if($booking->celebrity->bio)
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <strong class="text-white">About:</strong>
                            <p class="mt-2 text-muted">{{ Str::limit($booking->celebrity->bio, 200) }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Payment Information -->
        @if($booking->payment_required)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Method:</strong></div>
                        <div class="col-sm-8 text-white">
                            @if($booking->payment_method === 'stripe')
                                <span class="badge bg-info">Credit Card (Stripe)</span>
                            @elseif($booking->payment_method === 'crypto')
                                <span class="badge bg-warning">Cryptocurrency</span>
                            @elseif($booking->payment_method === 'bank')
                                <span class="badge bg-success">Bank Transfer</span>
                            @else
                                <span class="badge bg-secondary">{{ $booking->payment_method }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Status:</strong></div>
                        <div class="col-sm-8 text-white">
                            @if($booking->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($booking->payment_status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </div>
                    </div>
                    @if($booking->payment_transaction_id)
                        <div class="row mb-3">
                            <div class="col-sm-4 text-white"><strong>Transaction ID:</strong></div>
                            <div class="col-sm-8 text-white"><code>{{ $booking->payment_transaction_id }}</code></div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-4 text-white"><strong>Amount:</strong></div>
                        <div class="col-sm-8 text-white"><strong class="text-warning">${{ number_format($booking->total_amount ?? 0, 2) }}</strong></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Admin Actions -->
@if($booking->status === 'pending')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pending Action Required
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3 text-white">This booking is waiting for your review. Please approve or reject it.</p>
                    <div class="d-flex gap-3">
                        <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-success btn-lg" 
                                    onclick="return confirm('Are you sure you want to approve this booking? The customer will be notified by email.')">
                                <i class="fas fa-check me-2"></i>
                                Approve Booking
                            </button>
                        </form>
                        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-danger btn-lg" 
                                    onclick="return confirm('Are you sure you want to reject this booking? The customer will be notified by email.')">
                                <i class="fas fa-times me-2"></i>
                                Reject Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Timeline -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Booking Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Booking Submitted</h6>
                            <p class="timeline-description">Customer submitted the booking request</p>
                            <span class="timeline-date">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                    </div>
                    
                    @if($booking->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $booking->status === 'approved' ? 'bg-success' : 'bg-danger' }}"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Booking {{ ucfirst($booking->status) }}</h6>
                                <p class="timeline-description">Admin {{ $booking->status }} the booking request</p>
                                <span class="timeline-date">{{ $booking->updated_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #404040;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #1a1a1a;
}

.timeline-content {
    background: #2d2d2d;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #ffd700;
}

.timeline-title {
    margin-bottom: 5px;
    color: #ffd700;
}

.timeline-description {
    margin-bottom: 5px;
    color: #b0b0b0;
}

.timeline-date {
    font-size: 0.9em;
    color: #888;
}
</style>
@endpush