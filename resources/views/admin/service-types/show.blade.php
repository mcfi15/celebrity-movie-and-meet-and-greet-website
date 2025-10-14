@extends('layouts.admin')

@section('title', $serviceType->name)
@section('page-title', 'Service Type Details')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.service-types.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <a href="{{ route('admin.service-types.edit', $serviceType) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>
            Edit
        </a>
        <form action="{{ route('admin.service-types.destroy', $serviceType) }}" 
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
    <!-- Service Type Info -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Service Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h3 class="mb-2 text-white">{{ $serviceType->name }}</h3>
                        @if($serviceType->is_active)
                            <span class="badge bg-success fs-6">Active</span>
                        @else
                            <span class="badge bg-danger fs-6">Inactive</span>
                        @endif
                    </div>
                    <div class="col-md-4 text-end">
                        <small class="text-muted">Created {{ $serviceType->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-white">Description:</h6>
                    <p class="text-muted">{{ $serviceType->description }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h4 class="text-warning">{{ $serviceType->celebrityServices->count() }}</h4>
                        <p class="text-muted">Celebrities Offering</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h4 class="text-success">{{ $serviceType->bookings->count() }}</h4>
                        <p class="text-muted">Total Bookings</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h4 class="text-info">{{ $serviceType->bookings->where('status', 'approved')->count() }}</h4>
                        <p class="text-muted">Approved Bookings</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Celebrities Offering This Service -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Celebrities Offering This Service ({{ $serviceType->celebrityServices->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($serviceType->celebrityServices->count() > 0)
                    <div class="row">
                        @foreach($serviceType->celebrityServices as $celebrityService)
                            <div class="col-md-6 mb-3">
                                <div class="card bg-dark border-secondary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            @if($celebrityService->celebrity->image)
                                                <img src="{{ asset('storage/' . $celebrityService->celebrity->image) }}" 
                                                     alt="{{ $celebrityService->celebrity->name }}" 
                                                     class="rounded-circle me-3" 
                                                     width="50" 
                                                     height="50" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $celebrityService->celebrity->name }}</h6>
                                                <small class="text-muted">{{ $celebrityService->celebrity->profession }}</small>
                                                @if($celebrityService->celebrity->is_available)
                                                    <span class="badge bg-success ms-2">Available</span>
                                                @else
                                                    <span class="badge bg-danger ms-2">Unavailable</span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.celebrities.show', $celebrityService->celebrity) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h6>No Celebrities Offering This Service</h6>
                        <p>No celebrities have been assigned to offer this service yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.service-types.edit', $serviceType) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Service
                    </a>
                    <a href="{{ route('admin.bookings.index') }}?service={{ $serviceType->id }}" class="btn btn-info">
                        <i class="fas fa-calendar-check me-2"></i>
                        View Bookings
                    </a>
                    <a href="{{ route('admin.celebrities.index') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        Add to Celebrity
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Recent Bookings
                </h6>
            </div>
            <div class="card-body">
                @if($serviceType->bookings->count() > 0)
                    @foreach($serviceType->bookings->take(5) as $booking)
                        <div class="border-bottom border-secondary pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <small class="text-muted">#{{ $booking->id }}</small>
                                    <p class="mb-1"><strong>{{ $booking->customer_name }}</strong></p>
                                    <small class="text-muted">{{ $booking->celebrity->name }}</small>
                                </div>
                                <div class="text-end">
                                    @if($booking->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($booking->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $booking->created_at->format('M d') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($serviceType->bookings->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.bookings.index') }}?service={{ $serviceType->id }}" 
                               class="btn btn-sm btn-primary">
                                View All Bookings
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p class="small">No bookings yet</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Service Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Service Analytics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h5 class="text-success">{{ $serviceType->bookings->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">Approved</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="text-warning">{{ $serviceType->bookings->where('status', 'pending')->count() }}</h5>
                        <small class="text-muted">Pending</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-danger">{{ $serviceType->bookings->where('status', 'rejected')->count() }}</h5>
                        <small class="text-muted">Rejected</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-info">{{ $serviceType->celebrityServices->where('celebrity.is_available', true)->count() }}</h5>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this service type? This will also affect all related bookings and celebrity services.');
    }
</script>
@endpush