@extends('layouts.admin')

@section('title', 'Edit Celebrity')
@section('page-title', 'Edit Celebrity: ' . $celebrity->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.celebrities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <a href="{{ route('admin.celebrities.show', $celebrity) }}" class="btn btn-info">
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
                    Edit Celebrity Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.celebrities.update', $celebrity) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $celebrity->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profession" class="form-label">Profession *</label>
                                <input type="text" 
                                       class="form-control @error('profession') is-invalid @enderror" 
                                       id="profession" 
                                       name="profession" 
                                       value="{{ old('profession', $celebrity->profession) }}" 
                                       placeholder="e.g. Actor, Singer, Athlete" 
                                       required>
                                @error('profession')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category" 
                                        required>
                                    <option value="">Select Category</option>
                                    <option value="Hollywood" {{ old('category', $celebrity->category) === 'Hollywood' ? 'selected' : '' }}>Hollywood</option>
                                    <option value="Music" {{ old('category', $celebrity->category) === 'Music' ? 'selected' : '' }}>Music</option>
                                    <option value="Sports" {{ old('category', $celebrity->category) === 'Sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="Television" {{ old('category', $celebrity->category) === 'Television' ? 'selected' : '' }}>Television</option>
                                    <option value="Comedy" {{ old('category', $celebrity->category) === 'Comedy' ? 'selected' : '' }}>Comedy</option>
                                    <option value="Social Media" {{ old('category', $celebrity->category) === 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                    <option value="Other" {{ old('category', $celebrity->category) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate ($) *</label>
                                <input type="number" 
                                       class="form-control @error('hourly_rate') is-invalid @enderror" 
                                       id="hourly_rate" 
                                       name="hourly_rate" 
                                       value="{{ old('hourly_rate', $celebrity->hourly_rate) }}" 
                                       min="0" 
                                       step="0.01" 
                                       required>
                                @error('hourly_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="bio" class="form-label">Biography</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="4" 
                                  placeholder="Tell us about this celebrity...">{{ old('bio', $celebrity->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <div class="form-text">Leave empty to keep current image. Upload a new image to replace it.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_available" 
                                   name="is_available" 
                                   value="1" 
                                   {{ old('is_available', $celebrity->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                Available for Booking
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Available Services</label>
                        <div class="row">
                            @foreach($serviceTypes as $serviceType)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="service_{{ $serviceType->id }}" 
                                               name="service_types[]" 
                                               value="{{ $serviceType->id }}" 
                                               {{ in_array($serviceType->id, old('service_types', $celebrity->services->pluck('service_type_id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service_{{ $serviceType->id }}">
                                            {{ $serviceType->name }}
                                            <small class="text-muted">({{ $serviceType->description }})</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('service_types')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.celebrities.show', $celebrity) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Celebrity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Current Image -->
        @if($celebrity->image)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-image me-2"></i>
                        Current Image
                    </h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $celebrity->image) }}" 
                         alt="{{ $celebrity->name }}" 
                         class="img-fluid rounded" 
                         style="max-height: 200px;">
                </div>
            </div>
        @endif
        
        <!-- Stats -->
        <div class="card {{ $celebrity->image ? 'mt-4' : '' }}">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-warning">{{ $celebrity->bookings->count() }}</h5>
                        <small class="text-muted">Total Bookings</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">{{ $celebrity->bookings->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-info">{{ $celebrity->services->count() }}</h5>
                        <small class="text-muted">Services</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-primary">{{ $celebrity->created_at->diffForHumans() }}</h5>
                        <small class="text-muted">Added</small>
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
                    <a href="{{ route('admin.bookings.index') }}?celebrity={{ $celebrity->id }}" class="btn btn-info btn-sm">
                        <i class="fas fa-calendar-check me-2"></i>
                        View Bookings
                    </a>
                    <form action="{{ route('admin.celebrities.toggle-availability', $celebrity) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $celebrity->is_available ? 'btn-danger' : 'btn-success' }} btn-sm w-100">
                            @if($celebrity->is_available)
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
</div>
@endsection