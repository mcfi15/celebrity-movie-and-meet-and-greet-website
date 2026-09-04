@extends('layouts.admin')

@section('title', 'Bookings')
@section('page-title', 'Manage Bookings')

@section('content')
<!-- Filter Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="pending_payment_verification" {{ request('status') === 'pending_payment_verification' ? 'selected' : '' }}>Pending Payment Verification</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="celebrity" class="form-label">Celebrity</label>
                <select class="form-select" id="celebrity" name="celebrity">
                    <option value="">All Celebrities</option>
                    @foreach($celebrities as $celebrity)
                        <option value="{{ $celebrity->id }}" {{ request('celebrity') == $celebrity->id ? 'selected' : '' }}>
                            {{ $celebrity->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="service" class="form-label">Service Type</label>
                <select class="form-select" id="service" name="service">
                    <option value="">All Services</option>
                    @foreach($serviceTypes as $serviceType)
                        <option value="{{ $serviceType->id }}" {{ request('service') == $serviceType->id ? 'selected' : '' }}>
                            {{ $serviceType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Customer name, email...">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-warning">{{ $totalBookings }}</h5>
                        <p class="card-text text-white">Total Bookings</p>
                    </div>
                    <i class="fas fa-calendar-check fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-success">{{ $pendingBookings }}</h5>
                        <p class="card-text text-white">Pending</p>
                    </div>
                    <i class="fas fa-clock fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-info">{{ $pendingVerificationBookings }}</h5>
                        <p class="card-text text-white">Awaiting Payment Verification</p>
                    </div>
                    <i class="fas fa-wallet fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-danger">{{ $approvedBookings }}</h5>
                        <p class="card-text text-white">Approved</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-calendar-check me-2"></i>
            Bookings List
            @if(request()->hasAny(['status', 'celebrity', 'service', 'search']))
                <span class="badge bg-info ms-2">Filtered</span>
            @endif
        </h5>
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Booking
        </a>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Celebrity</th>
                            <th>Service</th>
                            <th>Event Date</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <strong>#{{ $booking->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $booking->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $booking->customer_email }}</small><br>
                                    <small class="text-muted">{{ $booking->customer_phone }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($booking->celebrity->image)
                                        <img src="{{ asset( $booking->celebrity->image) }}" 
                                             alt="{{ $booking->celebrity->name }}" 
                                             class="rounded-circle me-2" 
                                             width="30" 
                                             height="30" 
                                             style="object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $booking->celebrity->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->celebrity->profession }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $booking->serviceType->name }}</span>
                            </td>
                            <td>
                                @if($booking->event_date)
                                    {{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->event_time)->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->duration_hours)
                                    {{ $booking->duration_hours }} hour(s)
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($booking->status === 'pending_payment_verification')
                                    <span class="badge bg-info">Awaiting Verification</span>
                                @elseif($booking->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->payment_required)
                                    @if($booking->payment_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($booking->payment_status === 'pending_verification')
                                        <span class="badge bg-info">Pending Verification</span>
                                    @elseif($booking->payment_status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Not Required</span>
                                @endif
                            </td>
                            <td>
                                {{ $booking->created_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $booking->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Edit Booking">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.approve', $booking) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success" 
                                                    title="Approve" 
                                                    onclick="return confirm('Are you sure you want to approve this booking?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.reject', $booking) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Reject" 
                                                    onclick="return confirm('Are you sure you want to reject this booking?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @elseif($booking->status === 'pending_payment_verification')
                                        <form action="{{ route('admin.bookings.approve-payment', $booking) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success" 
                                                    title="Verify Payment & Approve"
                                                    onclick="return confirm('Verify this payment and approve the booking?')">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.reject-payment', $booking) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Reject Payment"
                                                    onclick="return confirm('Reject this payment and decline the booking?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <h5>No Bookings Found</h5>
                @if(request()->hasAny(['status', 'celebrity', 'service', 'search']))
                    <p>No bookings match your current filters.</p>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>
                        Clear Filters
                    </a>
                @else
                    <p>No bookings have been submitted yet.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this booking? This action cannot be undone.');
    }
</script>
@endpush