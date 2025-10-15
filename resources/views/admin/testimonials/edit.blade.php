@extends('layouts.admin')

@section('title', 'Edit Testimonial')
@section('page-title', 'Edit Testimonial')

@section('page-actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Testimonials
    </a>
    <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-info">
        <i class="fas fa-eye me-2"></i>
        View Testimonial
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Testimonial #{{ $testimonial->id }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $testimonial->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position/Title</label>
                                <input type="text" 
                                       class="form-control @error('position') is-invalid @enderror" 
                                       id="position" 
                                       name="position" 
                                       value="{{ old('position', $testimonial->position) }}" 
                                       placeholder="e.g., CEO, Customer, etc.">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Testimonial Message <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" 
                                  name="message" 
                                  rows="4" 
                                  required>{{ old('message', $testimonial->message) }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                <select class="form-select @error('rating') is-invalid @enderror" 
                                        id="rating" 
                                        name="rating" 
                                        required>
                                    <option value="">Select Rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                                            {{ $i }} Star{{ $i > 1 ? 's' : '' }} ({{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }})
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Customer Photo</label>
                                
                                @if($testimonial->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" 
                                             alt="{{ $testimonial->name }}" 
                                             class="img-thumbnail"
                                             style="max-width: 150px; max-height: 150px;">
                                        <div class="small text-muted">Current image</div>
                                    </div>
                                @endif
                                
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text text-white">
                                    @if($testimonial->image)
                                        Upload a new image to replace the current one
                                    @else
                                        Recommended: Square image, max 2MB (JPEG, PNG, GIF)
                                    @endif
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_featured" 
                                           name="is_featured" 
                                           value="1" 
                                           {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="is_featured">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        Featured Testimonial
                                    </label>
                                    <div class="form-text text-white">Featured testimonials are highlighted prominently on the website</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="is_active">
                                        <i class="fas fa-eye text-success me-1"></i>
                                        Active (Visible on website)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Testimonial
                        </button>
                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>
                            View
                        </a>
                        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Current Status
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">Status:</span>
                        <span class="badge {{ $testimonial->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">Featured:</span>
                        <span class="badge {{ $testimonial->is_featured ? 'bg-warning' : 'bg-secondary' }}">
                            {{ $testimonial->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">Rating:</span>
                        <span class="text-warning">
                            {!! $testimonial->stars !!}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">Created:</span>
                        <span class="small text-muted">{{ $testimonial->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white">Last Updated:</span>
                        <span class="small text-muted">{{ $testimonial->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Quick Tips
                </h6>
            </div>
            <div class="card-body text-white">
                <ul class="small mb-0">
                    <li>Use authentic customer feedback</li>
                    <li>Keep testimonials concise but meaningful</li>
                    <li>Featured testimonials appear prominently</li>
                    <li>High-quality photos improve credibility</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove existing preview
                const existingPreview = document.getElementById('image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                // Create new preview
                const preview = document.createElement('div');
                preview.id = 'image-preview';
                preview.className = 'mt-2';
                preview.innerHTML = `
                    <div class="border p-2 rounded">
                        <img src="${e.target.result}" 
                             class="img-thumbnail" 
                             style="max-width: 150px; max-height: 150px;">
                        <div class="small text-muted mt-1">New image preview</div>
                    </div>
                `;
                
                // Insert after the file input
                document.getElementById('image').parentNode.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
