@extends('layouts.admin')

@section('title', 'Sliders Management')
@section('page-title', 'Sliders Management')

@section('page-actions')
<a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Add New Slider
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-images me-2"></i>
                    Home Page Sliders
                </h5>
            </div>
            <div class="card-body">
                @if($sliders->count() > 0)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Tip:</strong> Drag and drop sliders to reorder them. The order here determines the display order on the home page.
                    </div>

                    <div id="sliders-container" class="row sortable">
                        @foreach($sliders as $slider)
                        <div class="col-md-6 col-lg-4 mb-4 sortable-item" data-slider-id="{{ $slider->id }}">
                            <div class="card h-100 slider-card">
                                <div class="position-relative">
                                    <img src="{{ asset($slider->image_path) }}" class="card-img-top slider-thumbnail" alt="{{ $slider->title }}">
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="position-absolute top-0 start-0 p-2">
                                        <span class="badge bg-primary">#{{ $slider->order_position }}</span>
                                    </div>
                                    <div class="drag-handle position-absolute bottom-0 start-0 p-2">
                                        <i class="fas fa-grip-vertical text-white" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);"></i>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title text-truncate">{{ $slider->title }}</h6>
                                    @if($slider->subtitle)
                                        <p class="card-text text-muted small">{{ Str::limit($slider->subtitle, 50) }}</p>
                                    @endif
                                    
                                    @if($slider->hasCta())
                                        <div class="mb-2">
                                            <small class="text-info">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                CTA: {{ $slider->cta_text }}
                                            </small>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-auto">
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('admin.sliders.show', $slider) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm {{ $slider->is_active ? 'btn-secondary' : 'btn-success' }} toggle-status" 
                                                    data-slider-id="{{ $slider->id }}" 
                                                    title="{{ $slider->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $slider->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-slider" 
                                                    data-slider-id="{{ $slider->id }}" 
                                                    data-slider-title="{{ $slider->title }}"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Sliders Yet</h4>
                        <p class="text-muted">Create your first slider to enhance your home page.</p>
                        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create First Slider
                        </a>
                    </div>
                @endif
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
                <p>Are you sure you want to delete the slider "<span id="slider-title-to-delete"></span>"?</p>
                <p class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
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
    .slider-thumbnail {
        height: 200px;
        object-fit: cover;
    }
    
    .slider-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: move;
    }
    
    .slider-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    
    .sortable-item.ui-sortable-helper {
        transform: rotate(5deg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    
    .sortable-item.ui-sortable-placeholder {
        border: 2px dashed var(--admin-accent);
        background: rgba(255, 215, 0, 0.1);
        visibility: visible !important;
        height: 300px;
    }
    
    .drag-handle {
        cursor: grab;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .drag-handle:hover {
        opacity: 1;
    }
    
    .drag-handle:active {
        cursor: grabbing;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize sortable
    $("#sliders-container").sortable({
        items: ".sortable-item",
        handle: ".drag-handle",
        placeholder: "sortable-item ui-sortable-placeholder col-md-6 col-lg-4 mb-4",
        update: function(event, ui) {
            var sliderIds = [];
            $(this).children('.sortable-item').each(function() {
                sliderIds.push($(this).data('slider-id'));
            });
            
            // Update order via AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.post('{{ route("admin.sliders.update-order") }}', {
                slider_ids: sliderIds
            })
            .done(function(response) {
                if (response.success) {
                    // Update order badges
                    $('#sliders-container .sortable-item').each(function(index) {
                        $(this).find('.badge.bg-primary').text('#' + (index + 1));
                    });
                    
                    // Show success message
                    showToast('success', response.message);
                }
            })
            .fail(function() {
                showToast('error', 'Failed to update slider order');
            });
        }
    });
    
    // Toggle status
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
                    // Update button appearance
                    if (response.is_active) {
                        button.removeClass('btn-success').addClass('btn-secondary');
                        button.find('i').removeClass('fa-play').addClass('fa-pause');
                        button.attr('title', 'Deactivate');
                        button.closest('.slider-card').find('.badge').removeClass('bg-secondary').addClass('bg-success').text('Active');
                    } else {
                        button.removeClass('btn-secondary').addClass('btn-success');
                        button.find('i').removeClass('fa-pause').addClass('fa-play');
                        button.attr('title', 'Activate');
                        button.closest('.slider-card').find('.badge').removeClass('bg-success').addClass('bg-secondary').text('Inactive');
                    }
                    
                    showToast('success', response.message);
                }
            })
            .fail(function() {
                showToast('error', 'Failed to update slider status');
            });
    });
    
    // Delete slider
    $('.delete-slider').click(function() {
        var sliderId = $(this).data('slider-id');
        var sliderTitle = $(this).data('slider-title');
        
        $('#slider-title-to-delete').text(sliderTitle);
        $('#delete-form').attr('action', '/admin/sliders/' + sliderId);
        $('#deleteModal').modal('show');
    });
    
    // Toast notification function
    function showToast(type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
        
        var toast = $(`
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="fas ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(toast);
        
        setTimeout(function() {
            toast.alert('close');
        }, 5000);
    }
});
</script>
@endpush
