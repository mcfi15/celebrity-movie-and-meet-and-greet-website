@extends('layouts.admin')

@section('title', 'Edit Slider')
@section('page-title', 'Edit Slider')

@section('page-actions')
<a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to Sliders
</a>
<a href="{{ route('admin.sliders.show', $slider) }}" class="btn btn-info">
    <i class="fas fa-eye me-2"></i>View Details
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Slider: {{ $slider->title }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" id="slider-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Current Image Display -->
                        <div class="col-12 mb-4">
                            <label class="form-label">
                                <i class="fas fa-image me-2"></i>Current Image
                            </label>
                            <div class="current-image-container">
                                <img src="{{ asset($slider->image_path) }}" alt="{{ $slider->title }}" 
                                     class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                        
                        <!-- New Image Upload -->
                        <div class="col-12 mb-4">
                            <label for="image" class="form-label">
                                <i class="fas fa-upload me-2"></i>Replace Image (Optional)
                            </label>
                            <div class="image-upload-container">
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <div class="form-text text-white">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Leave empty to keep current image. Recommended size: 1920x1080px (16:9 ratio). 
                                    Maximum file size: 5MB. Supported formats: JPEG, PNG, JPG, GIF.
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- New Image Preview -->
                                <div id="new-image-preview" class="mt-3" style="display: none;">
                                    <label class="form-label text-info">New Image Preview:</label>
                                    <img id="preview-img" src="" alt="New Preview" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-2"></i>Title *
                            </label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $slider->title) }}" required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Subtitle -->
                        <div class="col-md-6 mb-3">
                            <label for="subtitle" class="form-label">
                                <i class="fas fa-text-height me-2"></i>Subtitle
                            </label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" 
                                   id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" maxlength="255">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Optional description for the slider...">{{ old('description', $slider->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Call to Action -->
                        <div class="col-12 mb-3">
                            <div class="card bg-secondary">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-external-link-alt me-2"></i>
                                        Call to Action Button (Optional)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cta_text" class="form-label">Button Text</label>
                                            <input type="text" class="form-control @error('cta_text') is-invalid @enderror" 
                                                   id="cta_text" name="cta_text" value="{{ old('cta_text', $slider->cta_text) }}" 
                                                   placeholder="e.g., Learn More, Contact Us" maxlength="255">
                                            @error('cta_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="cta_link" class="form-label">Button Link</label>
                                            <input type="text" class="form-control @error('cta_link') is-invalid @enderror" 
                                                   id="cta_link" name="cta_link" value="{{ old('cta_link', $slider->cta_link) }}" 
                                                   placeholder="e.g., /contact, https://example.com" maxlength="255">
                                            @error('cta_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text text-white">
                                                Use relative URLs (/contact) for internal links or full URLs (https://example.com) for external links.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($slider->hasCta())
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Current CTA:</strong> "{{ $slider->cta_text }}" linking to "{{ $slider->cta_link }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Position -->
                        <div class="col-md-6 mb-3">
                            <label for="order_position" class="form-label">
                                <i class="fas fa-sort-numeric-up me-2"></i>Display Order
                            </label>
                            <input type="number" class="form-control @error('order_position') is-invalid @enderror" 
                                   id="order_position" name="order_position" value="{{ old('order_position', $slider->order_position) }}" 
                                   min="0">
                            @error('order_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-white">Lower numbers appear first.</div>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">
                                <i class="fas fa-toggle-on me-2"></i>Status
                            </label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $slider->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $slider->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-white">Only active sliders will be displayed on the home page.</div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Slider
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .current-image-container {
        border: 2px solid var(--admin-border);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        background: rgba(255, 215, 0, 0.05);
    }
    
    .image-upload-container {
        position: relative;
    }
    
    #new-image-preview {
        border: 2px dashed var(--admin-accent);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        background: rgba(255, 215, 0, 0.1);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: var(--admin-accent);
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    }
    
    .card .card-header {
        background: linear-gradient(135deg, var(--admin-accent), #ffed4a);
        color: var(--admin-primary);
    }
    
    .bg-secondary .card-header {
        background: var(--admin-secondary);
        color: var(--admin-text);
        border-bottom: 1px solid var(--admin-border);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // New image preview functionality
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#new-image-preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#new-image-preview').hide();
        }
    });
    
    // CTA validation
    $('#cta_text, #cta_link').on('input', function() {
        const ctaText = $('#cta_text').val().trim();
        const ctaLink = $('#cta_link').val().trim();
        
        // If one is filled, both should be filled
        if (ctaText && !ctaLink) {
            $('#cta_link').addClass('is-invalid');
            if (!$('#cta_link').next('.invalid-feedback').length) {
                $('#cta_link').after('<div class="invalid-feedback">Link is required when button text is provided.</div>');
            }
        } else if (ctaLink && !ctaText) {
            $('#cta_text').addClass('is-invalid');
            if (!$('#cta_text').next('.invalid-feedback').length) {
                $('#cta_text').after('<div class="invalid-feedback">Button text is required when link is provided.</div>');
            }
        } else {
            $('#cta_text, #cta_link').removeClass('is-invalid');
            $('#cta_text, #cta_link').siblings('.invalid-feedback').remove();
        }
    });
    
    // Form validation
    $('#slider-form').submit(function(e) {
        const ctaText = $('#cta_text').val().trim();
        const ctaLink = $('#cta_link').val().trim();
        
        // Validate CTA fields
        if ((ctaText && !ctaLink) || (ctaLink && !ctaText)) {
            e.preventDefault();
            
            if (ctaText && !ctaLink) {
                $('#cta_link').focus();
                alert('Please provide a link for the call-to-action button.');
            } else {
                $('#cta_text').focus();
                alert('Please provide text for the call-to-action button.');
            }
            return false;
        }
    });
});
</script>
@endpush
