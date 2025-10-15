@extends('layouts.admin')

@section('title', $celebrity->name)
@section('page-title', 'Celebrity Details')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.celebrities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <a href="{{ route('admin.celebrities.edit', $celebrity) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>
            Edit
        </a>
        <form action="{{ route('admin.celebrities.destroy', $celebrity) }}" 
              method="POST" 
              class="d-inline" 
              onsubmit="return confirmDelete()">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>
                Delete
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Celebrity Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($celebrity->image)
                    <img src="{{ asset($celebrity->image) }}" 
                         alt="{{ $celebrity->name }}" 
                         class="rounded-circle mb-3" 
                         width="150" 
                         height="150" 
                         style="object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                @endif
                
                <h4 class="mb-1 text-white">{{ $celebrity->name }}</h4>
                <p class="text-white mb-3">{{ $celebrity->profession }}</p>
                
                <div class="mb-3">
                    @if($celebrity->is_active)
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>
                            Available
                        </span>
                    @else
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-times-circle me-1"></i>
                            Unavailable
                        </span>
                    @endif
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-warning">${{ number_format($celebrity->hourly_rate, 2) }}</h5>
                        <small class="text-white">Hourly Rate</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-info">{{ $celebrity->bookings->count() }}</h5>
                        <small class="text-white">Total Bookings</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.celebrities.edit', $celebrity) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Details
                    </a>
                    <form action="{{ route('admin.celebrities.toggle-availability', $celebrity) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $celebrity->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                            @if($celebrity->is_active)
                                <i class="fas fa-times me-2"></i>
                                Mark Unavailable
                            @else
                                <i class="fas fa-check me-2"></i>
                                Mark Available
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Details & Stats -->
    <div class="col-md-8">
        <!-- Basic Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong class="text-white">Category:</strong>
                        <span class="badge bg-primary ms-2">{{ $celebrity->category }}</span>
                    </div>
                    <div class="col-md-6 text-white">
                        <strong>Added:</strong> {{ $celebrity->created_at->format('M d, Y') }}
                    </div>
                </div>
                
                @if($celebrity->bio)
                    <div class="mt-3">
                        <strong class="text-white">Biography:</strong>
                        <p class="mt-2 text-white">{{ $celebrity->bio }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Available Services -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Available Services ({{ $celebrity->services->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($celebrity->services->count() > 0)
                    <div class="row">
                        @foreach($celebrity->services as $service)
                            <div class="col-md-6 mb-2">
                                <div class="border border-secondary rounded p-2">
                                    <strong class="text-white">{{ $service->serviceType->name }}</strong>
                                    <p class="small text-white mb-0">{{ $service->serviceType->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-white py-3">
                        <i class="fas fa-list fa-2x mb-2"></i>
                        <p>No services assigned yet</p>
                        <a href="{{ route('admin.celebrities.edit', $celebrity) }}" class="btn btn-primary btn-sm">
                            Add Services
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Recent Bookings ({{ $celebrity->bookings->count() }} total)
                </h5>
            </div>
            <div class="card-body">
                @if($celebrity->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($celebrity->bookings->take(5) as $booking)
                                <tr>
                                    <td>{{ $booking->customer_name }}</td>
                                    <td>{{ $booking->serviceType->name }}</td>
                                    <td>
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($booking->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $booking->created_at->format('M d') }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($celebrity->bookings->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.bookings.index') }}?celebrity={{ $celebrity->id }}" class="btn btn-primary btn-sm">
                                View All Bookings
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-white py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p>No bookings yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this celebrity? This will also delete all associated bookings and cannot be undone.');
    }
</script>
@endpush