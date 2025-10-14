@extends('layouts.admin')

@section('title', 'Edit Booking')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Edit Booking #{{ $booking->id }}</h3>
                    <div>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Celebrity and Service Selection -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="celebrity_id" class="form-label">Celebrity *</label>
                                    <select class="form-control @error('celebrity_id') is-invalid @enderror" 
                                            id="celebrity_id" name="celebrity_id" required>
                                        <option value="">Select Celebrity</option>
                                        @foreach($celebrities as $celebrity)
                                            <option value="{{ $celebrity->id }}" 
                                                {{ (old('celebrity_id', $booking->celebrity_id) == $celebrity->id) ? 'selected' : '' }}>
                                                {{ $celebrity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('celebrity_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_type_id" class="form-label">Service Type *</label>
                                    <select class="form-control @error('service_type_id') is-invalid @enderror" 
                                            id="service_type_id" name="service_type_id" required>
                                        <option value="">Select Service Type</option>
                                        @foreach($serviceTypes as $service)
                                            <option value="{{ $service->id }}" 
                                                {{ (old('service_type_id', $booking->service_type_id) == $service->id) ? 'selected' : '' }}>
                                                {{ $service->name }} - ${{ number_format($service->base_price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <h5 class="mt-4 mb-3 text-white">Customer Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="customer_name" class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', $booking->customer_name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="customer_email" class="form-label">Customer Email *</label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" name="customer_email" 
                                           value="{{ old('customer_email', $booking->customer_email) }}" required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="customer_phone" class="form-label">Customer Phone *</label>
                                    <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" 
                                           value="{{ old('customer_phone', $booking->customer_phone) }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <h5 class="mt-4 mb-3 text-white">Event Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_date" class="form-label">Event Date *</label>
                                    <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                           id="event_date" name="event_date" 
                                           value="{{ old('event_date', $booking->event_date ? $booking->event_date->format('Y-m-d') : '') }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_time" class="form-label">Event Time *</label>
                                    <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                           id="event_time" name="event_time" 
                                           value="{{ old('event_time', $booking->event_time ? $booking->event_time->format('H:i') : '') }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="event_location" class="form-label">Event Location *</label>
                            <input type="text" class="form-control @error('event_location') is-invalid @enderror" 
                                   id="event_location" name="event_location" 
                                   value="{{ old('event_location', $booking->event_location) }}" 
                                   placeholder="Enter full address or venue name" required>
                            @error('event_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="event_description" class="form-label">Event Description *</label>
                            <textarea class="form-control @error('event_description') is-invalid @enderror" 
                                      id="event_description" name="event_description" rows="4" required 
                                      placeholder="Describe the event, type of performance needed, audience size, etc.">{{ old('event_description', $booking->event_description) }}</textarea>
                            @error('event_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status and Payment -->
                        <h5 class="mt-4 mb-3 text-white">Status Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Booking Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $booking->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $booking->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_status" class="form-label">Payment Status *</label>
                                    <select class="form-control @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                        <option value="pending" {{ old('payment_status', $booking->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ old('payment_status', $booking->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ old('payment_status', $booking->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ old('payment_status', $booking->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <h5 class="mt-4 mb-3 text-white">Additional Information</h5>
                        <div class="form-group mb-3">
                            <label for="special_requests" class="form-label">Special Requests</label>
                            <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                      id="special_requests" name="special_requests" rows="3" 
                                      placeholder="Any special requirements or requests from the customer">{{ old('special_requests', $booking->special_requests) }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                      id="admin_notes" name="admin_notes" rows="3" 
                                      placeholder="Internal notes for admin reference">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Timestamps -->
                        @if($booking->created_at)
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Created At</label>
                                    <input type="text" class="form-control" value="{{ $booking->created_at->format('M d, Y H:i') }}" readonly>
                                </div>
                            </div>
                            @if($booking->updated_at)
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Last Updated</label>
                                    <input type="text" class="form-control" value="{{ $booking->updated_at->format('M d, Y H:i') }}" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Booking
                        </button>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info ms-2">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.card-header h3 {
    color: #495057;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

h5 {
    color: #6c757d;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
}
</style>
@endsection
