@extends('layouts.admin')

@section('title', 'Slider Details')
@section('page-title', 'Slider Details')

@section('page-actions')
<a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to Sliders
</a>
<a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-warning">
    <i class="fas fa-edit me-2"></i>Edit Slider
</a>
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
    <i class="fas fa-trash me-2"></i>Delete Slider
</button>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Slider Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Slider Preview
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="slider-preview-container position-relative">
                    <img src="{{ asset($slider->image_path) }}" alt="{{ $slider->title }}" 
                         class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
                    
                    <!-- Overlay Content -->
                    <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-75">
                        <h1 class="display-4 fw-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                            {{ $slider->title }}
                        </h1>
                        
                        @if($slider->subtitle)
                            <h3 class="mb-4" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                                {{ $slider->subtitle }}
                            </h3>
                        @endif
                        
                        @if($slider->description)
                            <p class="lead mb-4" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                                {{ $slider->description }}
                            </p>
                        @endif
                        
                        @if($slider->hasCta())
                            <a href="{{ $slider->formatted_cta_link }}" class="btn btn-primary btn-lg">
                                {{ $slider->cta_text }}
                            </a>
                        @endif
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <!-- Order Badge -->
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-primary fs-6">Order: #{{ $slider->order_position }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Slider Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Slider Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-4 text-white"><strong>ID:</strong></div>
                    <div class="col-8 text-white">#{{ $slider->id }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-white"><strong>Title:</strong></div>
                    <div class="col-8 text-white">{{ $slider->title }}</div>
                </div>
                
                @if($slider->subtitle)
                    <div class="row mb-3">
                        <div class="col-4 text-white"><strong>Subtitle:</strong></div>
                        <div class="col-8 text-white">{{ $slider->subtitle }}</div>
                    </div>
                @endif
                
                @if($slider->description)
                    <div class="row mb-3">
                        <div class="col-4 text-white"><strong>Description:</strong></div>
                        <div class="col-8 text-white">{{ $slider->description }}</div>
                    </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-4 text-white"><strong>Status:</strong></div>
                    <div class="col-8 text-white">
                        <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-white"><strong>Order:</strong></div>
                    <div class="col-8 text-white">#{{ $slider->order_position }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-white"><strong>Created:</strong></div>
                    <div class="col-8 text-white">{{ $slider->created_at->format('M d, Y H:i') }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-white"><strong>Updated:</strong></div>
                    <div class="col-8 text-white">{{ $slider->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action Information -->
        @if($slider->cta_text || $slider->cta_link)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Call to Action
                    </h5>
                </div>
                <div class="card-body">
                    @if($slider->cta_text)
                        <div class="row mb-3">
                            <div class="col-4 text-white"><strong>Button Text:</strong></div>
                            <div class="col-8 text-white">{{ $slider->cta_text }}</div>
                        </div>
                    @endif
                    
                    @if($slider->cta_link)
                        <div class="row mb-3">
                            <div class="col-4 text-white"><strong>Link:</strong></div>
                            <div class="col-8 text-white">
                                <a href="{{ $slider->formatted_cta_link }}" target="_blank" class="text-break">
                                    {{ $slider->cta_link }}
                                    <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if($slider->hasCta())
                        <div class="text-center mt-3">
                            <a href="{{ $slider->formatted_cta_link }}" target="_blank" class="btn btn-primary">
                                Test Button: {{ $slider->cta_text }}
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No call-to-action button configured
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-{{ $slider->is_active ? 'warning' : 'success' }} toggle-status" 
                            data-slider-id="{{ $slider->id }}">
                        <i class="fas fa-{{ $slider->is_active ? 'pause' : 'play' }} me-2"></i>
                        {{ $slider->is_active ? 'Deactivate' : 'Activate' }} Slider
                    </button>
                    
                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-info">
                        <i class="fas fa-edit me-2"></i>Edit Details
                    </a>
                    
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Create New Slider
                    </a>
                    
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-home me-2"></i>View Home Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the slider "<strong>{{ $slider->title }}</strong>"?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone and will permanently remove the slider from your home page.
                </p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Slider</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .slider-preview-container {
        background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        min-height: 400px;
        border-radius: 0 0 10px 10px;
        overflow: hidden;
    }
    
    .slider-preview-container img {
        border-radius: 0 0 10px 10px;
    }
    
    .card .card-header {
        background: linear-gradient(135deg, var(--admin-accent), #ffed4a);
        color: var(--admin-primary);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--admin-accent), #ffed4a);
        border-color: var(--admin-accent);
        color: var(--admin-primary);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #ffed4a, var(--admin-accent));
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status functionality
    $('.toggle-status').click(function() {
        var sliderId = $(this).data('slider-id');
        var button = $(this);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.post('/admin/sliders/' + sliderId + '/toggle-status')
            .done(function(response) {
                if (response.success) {
                    // Reload page to reflect changes
                    location.reload();
                }
            })
            .fail(function() {
                alert('Failed to update slider status. Please try again.');
            });
    });
});
</script>
@endpush
