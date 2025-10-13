@extends('layouts.admin')

@section('title', 'Add Celebrity')
@section('page-title', 'Add New Celebrity')

@section('page-actions')
    <a href="{{ route('admin.celebrities.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to List
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Celebrity Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.celebrities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
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
                                       value="{{ old('profession') }}" 
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
                                    <option value="Hollywood" {{ old('category') === 'Hollywood' ? 'selected' : '' }}>Hollywood</option>
                                    <option value="Music" {{ old('category') === 'Music' ? 'selected' : '' }}>Music</option>
                                    <option value="Sports" {{ old('category') === 'Sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="Television" {{ old('category') === 'Television' ? 'selected' : '' }}>Television</option>
                                    <option value="Comedy" {{ old('category') === 'Comedy' ? 'selected' : '' }}>Comedy</option>
                                    <option value="Social Media" {{ old('category') === 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                    <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
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
                                       value="{{ old('hourly_rate') }}" 
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
                                  placeholder="Tell us about this celebrity...">{{ old('bio') }}</textarea>
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
                        <div class="form-text">Upload a high-quality image (max 2MB). Recommended size: 400x400px</div>
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
                                   {{ old('is_available', true) ? 'checked' : '' }}>
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
                                               {{ in_array($serviceType->id, old('service_types', [])) ? 'checked' : '' }}>
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
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-undo me-2"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Save Celebrity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Quick Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-camera text-warning me-2"></i>
                        <small>Use high-quality, professional photos for better engagement</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-dollar-sign text-success me-2"></i>
                        <small>Set competitive hourly rates based on market standards</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-list text-info me-2"></i>
                        <small>Select multiple services to increase booking opportunities</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-pen text-primary me-2"></i>
                        <small>Write engaging biographies to attract more customers</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview logic here if needed
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush