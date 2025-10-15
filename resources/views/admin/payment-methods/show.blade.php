@extends('layouts.admin')

@section('title', 'Payment Method Details')
@section('page-title', 'Payment Method: ' . $paymentMethod->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
        <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <button type="button" class="btn btn-{{ $paymentMethod->is_active ? 'outline-secondary' : 'outline-success' }} toggle-status" 
                data-payment-method-id="{{ $paymentMethod->id }}">
            <i class="fas fa-{{ $paymentMethod->is_active ? 'pause' : 'play' }} me-2"></i>
            {{ $paymentMethod->is_active ? 'Deactivate' : 'Activate' }}
        </button>
        @if($bookingsCount == 0)
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Payment Method Status Overview -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div style="font-size: 48px;">
                            {!! $paymentMethod->icon_html !!}
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <h2 class="mb-1">
                            @if($paymentMethod->is_active)
                                <span class="badge bg-success fs-4">Active</span>
                            @else
                                <span class="badge bg-secondary fs-4">Inactive</span>
                            @endif
                        </h2>
                        <p class="text-muted">Status</p>
                    </div>
                    <div class="col-md-2 text-center">
                        <h2 class="mb-1 text-info">{{ $bookingsCount }}</h2>
                        <p class="text-muted">Total Bookings</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-success">${{ number_format($totalRevenue, 2) }}</h2>
                        <p class="text-muted">Revenue Generated</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-primary">{{ $paymentMethod->formatted_processing_fee }}</h2>
                        <p class="text-muted">Processing Fee</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Basic Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Basic Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Name:</strong></td>
                        <td>{{ $paymentMethod->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug:</strong></td>
                        <td><code>{{ $paymentMethod->slug }}</code></td>
                    </tr>
                    @if($paymentMethod->description)
                        <tr>
                            <td><strong>Description:</strong></td>
                            <td>{{ $paymentMethod->description }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Color:</strong></td>
                        <td>
                            <span class="badge" style="background-color: {{ $paymentMethod->color }};">
                                {{ $paymentMethod->color }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Sort Order:</strong></td>
                        <td><span class="badge bg-secondary">{{ $paymentMethod->sort_order }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $paymentMethod->created_at->format('M d, Y \a\t h:i A') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $paymentMethod->updated_at->format('M d, Y \a\t h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Processing Fees -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-percent me-2"></i>
                    Fee Structure
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Percentage Fee:</strong></td>
                        <td>
                            @if($paymentMethod->processing_fee_percentage > 0)
                                {{ $paymentMethod->processing_fee_percentage }}%
                            @else
                                <span class="text-muted">No percentage fee</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Fixed Fee:</strong></td>
                        <td>
                            @if($paymentMethod->processing_fee_fixed > 0)
                                ${{ number_format($paymentMethod->processing_fee_fixed, 2) }}
                            @else
                                <span class="text-muted">No fixed fee</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Fee:</strong></td>
                        <td><strong>{{ $paymentMethod->formatted_processing_fee }}</strong></td>
                    </tr>
                </table>
                
                @if($paymentMethod->processing_fee_percentage > 0 || $paymentMethod->processing_fee_fixed > 0)
                    <div class="mt-3">
                        <h6>Fee Examples:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Fee</th>
                                        <th>Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([50, 100, 500, 1000] as $amount)
                                        @php
                                            $fee = $paymentMethod->calculateProcessingFee($amount);
                                            $net = $amount - $fee;
                                        @endphp
                                        <tr>
                                            <td>${{ number_format($amount, 2) }}</td>
                                            <td>${{ number_format($fee, 2) }}</td>
                                            <td>${{ number_format($net, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Configuration & Limits -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>
                    Amount Limits
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Minimum:</strong></td>
                        <td>
                            @if($paymentMethod->minimum_amount)
                                ${{ number_format($paymentMethod->minimum_amount, 2) }}
                            @else
                                <span class="text-muted">No minimum</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Maximum:</strong></td>
                        <td>
                            @if($paymentMethod->maximum_amount)
                                ${{ number_format($paymentMethod->maximum_amount, 2) }}
                            @else
                                <span class="text-muted">No maximum</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Summary:</strong></td>
                        <td><strong>{{ $paymentMethod->amount_limits_text }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($paymentMethod->instructions)
            <!-- Customer Instructions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info me-2"></i>
                        Customer Instructions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-dark rounded border">
                        {!! nl2br(e($paymentMethod->instructions)) !!}
                    </div>
                </div>
            </div>
        @endif
        
        <!-- API Configuration -->
        @if($paymentMethod->api_key || $paymentMethod->webhook_url)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        API Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        @if($paymentMethod->api_key)
                            <tr>
                                <td width="30%"><strong>API Key:</strong></td>
                                <td>
                                    <code>{{ Str::mask($paymentMethod->api_key, '*', 4, -4) }}</code>
                                </td>
                            </tr>
                        @endif
                        @if($paymentMethod->api_secret)
                            <tr>
                                <td><strong>API Secret:</strong></td>
                                <td><code>••••••••••••</code></td>
                            </tr>
                        @endif
                        @if($paymentMethod->webhook_url)
                            <tr>
                                <td><strong>Webhook URL:</strong></td>
                                <td>
                                    <a href="{{ $paymentMethod->webhook_url }}" target="_blank" class="text-warning">
                                        {{ $paymentMethod->webhook_url }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Recent Bookings -->
@if($bookingsCount > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Recent Bookings
                    </h5>
                    <a href="{{ route('admin.bookings.index') }}?payment_method={{ $paymentMethod->slug }}" 
                       class="btn btn-sm btn-outline-primary">
                        View All Bookings
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking #</th>
                                    <th>Customer</th>
                                    <th>Celebrity</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentMethod->bookings()->with(['celebrity'])->latest()->limit(5)->get() as $booking)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-warning">
                                                {{ $booking->booking_number }}
                                            </a>
                                        </td>
                                        <td>{{ $booking->customer_name }}</td>
                                        <td>{{ $booking->celebrity->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($booking->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No bookings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the payment method "<strong>{{ $paymentMethod->name }}</strong>"?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone and will permanently remove this payment method.
                </p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Payment Method</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status functionality
    $('.toggle-status').click(function() {
        var paymentMethodId = $(this).data('payment-method-id');
        var button = $(this);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.post('/admin/payment-methods/' + paymentMethodId + '/toggle-status')
            .done(function(response) {
                console.log('Toggle response:', response);
                if (response.success) {
                    // Reload page to reflect changes
                    location.reload();
                } else {
                    alert('Failed to update status: ' + (response.message || 'Unknown error'));
                }
            })
            .fail(function(xhr, status, error) {
                console.error('Toggle failed:', xhr.responseText);
                alert('Failed to update payment method status: ' + (xhr.responseJSON?.message || error));
            });
    });
});
</script>
@endpush
