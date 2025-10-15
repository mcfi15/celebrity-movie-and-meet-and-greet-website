@extends('layouts.admin')

@section('title', 'Testimonials')
@section('page-title', 'Manage Testimonials')

@section('page-actions')
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Add New Testimonial
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-quote-right me-2"></i>
            Testimonials List
        </h5>
    </div>
    <div class="card-body">
        @if($testimonials->count() > 0)
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Message</th>
                            <th>Rating</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $testimonial)
                        <tr>
                            <td>#{{ $testimonial->id }}</td>
                            <td>
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" 
                                         alt="{{ $testimonial->name }}" 
                                         class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $testimonial->name }}</strong></td>
                            <td>{{ $testimonial->position ?? '-' }}</td>
                            <td>{{ Str::limit($testimonial->message, 50) }}</td>
                            <td>
                                <div class="text-warning">
                                    {!! $testimonial->stars !!}
                                </div>
                                <small class="text-muted">({{ $testimonial->rating }}/5)</small>
                            </td>
                            <td>
                                <button class="btn btn-sm {{ $testimonial->is_featured ? 'btn-warning' : 'btn-outline-warning' }} toggle-featured"
                                        data-id="{{ $testimonial->id }}" 
                                        title="Toggle Featured">
                                    <i class="fas fa-star"></i>
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm {{ $testimonial->is_active ? 'btn-success' : 'btn-outline-danger' }} toggle-status"
                                        data-id="{{ $testimonial->id }}" 
                                        title="Toggle Status">
                                    <i class="fas {{ $testimonial->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $testimonials->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-quote-right fa-3x mb-3"></i>
                <h5>No Testimonials Found</h5>
                <p>Start by adding your first testimonial to showcase customer feedback.</p>
                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add First Testimonial
                </a>
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
                    // Update button appearance
                    if (response.is_featured) {
                        button.removeClass('btn-outline-warning').addClass('btn-warning');
                    } else {
                        button.removeClass('btn-warning').addClass('btn-outline-warning');
                    }
                    
                    // Show success message
                    showToast('success', response.message);
                }
            },
            error: function() {
                showToast('error', 'Failed to update testimonial featured status.');
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
                    // Update button appearance
                    const icon = button.find('i');
                    if (response.is_active) {
                        button.removeClass('btn-outline-danger').addClass('btn-success');
                        icon.removeClass('fa-toggle-off').addClass('fa-toggle-on');
                    } else {
                        button.removeClass('btn-success').addClass('btn-outline-danger');
                        icon.removeClass('fa-toggle-on').addClass('fa-toggle-off');
                    }
                    
                    // Show success message
                    showToast('success', response.message);
                }
            },
            error: function() {
                showToast('error', 'Failed to update testimonial status.');
            }
        });
    });

    function showToast(type, message) {
        // Simple toast notification function
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const toast = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('body').append(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 3000);
    }
</script>
@endpush
