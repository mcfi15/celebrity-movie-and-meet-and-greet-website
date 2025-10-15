@extends('layouts.admin')

@section('title', 'View Testimonial')
@section('page-title', 'Testimonial Details')

@section('page-actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Testimonials
    </a>
    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>
        Edit Testimonial
    </a>
    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" 
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
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-quote-right me-2"></i>
                    Testimonial #{{ $testimonial->id }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Testimonial Preview -->
                <div class="testimonial-preview p-4 bg-light rounded mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            @if($testimonial->image)
                                <img src="{{ asset('storage/' . $testimonial->image) }}" 
                                     alt="{{ $testimonial->name }}" 
                                     class="rounded-circle"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <blockquote class="blockquote mb-3">
                                <p class="mb-0">"{{ $testimonial->message }}"</p>
                            </blockquote>
                            <footer class="blockquote-footer">
                                <strong>{{ $testimonial->name }}</strong>
                                @if($testimonial->position)
                                    <cite title="Source Title">, {{ $testimonial->position }}</cite>
                                @endif
                            </footer>
                            <div class="mt-2">
                                <span class="text-warning h5">{!! $testimonial->stars !!}</span>
                                <span class="text-muted ms-2">({{ $testimonial->rating }}/5 stars)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-white">Customer Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $testimonial->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Position:</strong></td>
                                <td>{{ $testimonial->position ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rating:</strong></td>
                                <td>
                                    <span class="text-warning">{!! $testimonial->stars !!}</span>
                                    ({{ $testimonial->rating }}/5)
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-white">Status & Settings</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $testimonial->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Featured:</strong></td>
                                <td>
                                    <span class="badge {{ $testimonial->is_featured ? 'bg-warning' : 'bg-secondary' }}">
                                        {{ $testimonial->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Has Image:</strong></td>
                                <td>
                                    <span class="badge {{ $testimonial->image ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $testimonial->image ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="text-white">Testimonial Message</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $testimonial->message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn {{ $testimonial->is_featured ? 'btn-warning' : 'btn-outline-warning' }} toggle-featured"
                            data-id="{{ $testimonial->id }}">
                        <i class="fas fa-star me-2"></i>
                        {{ $testimonial->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                    </button>
                    
                    <button class="btn {{ $testimonial->is_active ? 'btn-outline-danger' : 'btn-success' }} toggle-status"
                            data-id="{{ $testimonial->id }}">
                        <i class="fas {{ $testimonial->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                        {{ $testimonial->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Testimonial
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-white">Created:</strong><br>
                    <span class="text-muted">{{ $testimonial->created_at->format('F d, Y \a\t H:i') }}</span>
                    <div class="small text-muted">{{ $testimonial->created_at->diffForHumans() }}</div>
                </div>
                
                @if($testimonial->updated_at != $testimonial->created_at)
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <span class="text-muted">{{ $testimonial->updated_at->format('F d, Y \a\t H:i') }}</span>
                        <div class="small text-muted">{{ $testimonial->updated_at->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        </div>

        @if($testimonial->image)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-image me-2"></i>
                    Customer Photo
                </h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $testimonial->image) }}" 
                     alt="{{ $testimonial->name }}" 
                     class="img-fluid rounded">
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $testimonial->image) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        View Full Size
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this testimonial? This action cannot be undone.');
    }

    // Toggle Featured Status
    $('.toggle-featured').on('click', function() {
        const testimonialId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `/admin/testimonials/${testimonialId}/toggle-featured`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update button appearance and text
                    if (response.is_featured) {
                        button.removeClass('btn-outline-warning').addClass('btn-warning');
                        button.html('<i class="fas fa-star me-2"></i>Remove from Featured');
                    } else {
                        button.removeClass('btn-warning').addClass('btn-outline-warning');
                        button.html('<i class="fas fa-star me-2"></i>Mark as Featured');
                    }
                    
                    // Update the status badge in the table
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to update testimonial featured status.');
            }
        });
    });

    // Toggle Active Status
    $('.toggle-status').on('click', function() {
        const testimonialId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `/admin/testimonials/${testimonialId}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update button appearance and text
                    if (response.is_active) {
                        button.removeClass('btn-success').addClass('btn-outline-danger');
                        button.html('<i class="fas fa-eye-slash me-2"></i>Deactivate');
                    } else {
                        button.removeClass('btn-outline-danger').addClass('btn-success');
                        button.html('<i class="fas fa-eye me-2"></i>Activate');
                    }
                    
                    // Reload to update all status indicators
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to update testimonial status.');
            }
        });
    });
</script>
@endpush
