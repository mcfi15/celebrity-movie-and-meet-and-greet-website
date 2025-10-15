@extends('layouts.admin')

@section('title', 'Add New Testimonial')
@section('page-title', 'Add New Testimonial')

@section('page-actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Testimonials
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Create New Testimonial
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Customer Name <span class="text-danger">*</span></label>
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
                                <label for="position" class="form-label">Position/Title</label>
                                <input type="text" 
                                       class="form-control @error('position') is-invalid @enderror" 
                                       id="position" 
                                       name="position" 
                                       value="{{ old('position') }}" 
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
                                  required>{{ old('message') }}</textarea>
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
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
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
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text text-white">Recommended: Square image, max 2MB (JPEG, PNG, GIF)</div>
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
                                           {{ old('is_featured') ? 'checked' : '' }}>
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
                                           {{ old('is_active', '1') ? 'checked' : '' }}>
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
                            Create Testimonial
                        </button>
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
                    Testimonial Guidelines
                </h6>
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <h6>Best Practices:</h6>
                    <ul class="small">
                        <li>Use authentic customer feedback</li>
                        <li>Include customer's name and position when possible</li>
                        <li>Keep testimonials concise but meaningful</li>
                        <li>Add customer photos for better credibility</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6>Image Requirements:</h6>
                    <ul class="small">
                        <li>Square aspect ratio (1:1) recommended</li>
                        <li>Minimum 200x200 pixels</li>
                        <li>Maximum file size: 2MB</li>
                        <li>Formats: JPEG, PNG, GIF</li>
                    </ul>
                </div>
                
                <div class="alert alert-info small">
                    <i class="fas fa-lightbulb me-1"></i>
                    <strong>Tip:</strong> Featured testimonials appear prominently on your homepage and key landing pages.
                </div>
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
                    <img src="${e.target.result}" 
                         class="img-thumbnail" 
                         style="max-width: 150px; max-height: 150px;">
                    <div class="small text-muted mt-1">Preview</div>
                `;
                
                // Insert after the file input
                e.target.parentNode.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
