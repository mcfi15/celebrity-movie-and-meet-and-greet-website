@extends('layouts.admin')

@section('title', 'Edit Service Type')
@section('page-title', 'Edit Service Type: ' . $serviceType->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.service-types.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <a href="{{ route('admin.service-types.show', $serviceType) }}" class="btn btn-info">
            <i class="fas fa-eye me-2"></i>
            View Details
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Service Type Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.service-types.update', $serviceType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Service Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $serviceType->name) }}" 
                               placeholder="e.g. Meet & Greet, Photo Session" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Describe what this service includes..." 
                                  required>{{ old('description', $serviceType->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $serviceType->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (available for booking)
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.service-types.show', $serviceType) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Service Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-warning">{{ $serviceType->celebrityServices->count() }}</h5>
                        <small class="text-muted">Celebrities</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">{{ $serviceType->bookings->count() }}</h5>
                        <small class="text-muted">Bookings</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <h5 class="text-primary">{{ $serviceType->created_at->diffForHumans() }}</h5>
                        <small class="text-muted">Created</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Current Status
                </h6>
            </div>
            <div class="card-body text-center">
                @if($serviceType->is_active)
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                        <h6>Active</h6>
                        <p class="small text-muted">Available for booking</p>
                    </div>
                @else
                    <div class="text-danger">
                        <i class="fas fa-times-circle fa-3x mb-2"></i>
                        <h6>Inactive</h6>
                        <p class="small text-muted">Not available for booking</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection