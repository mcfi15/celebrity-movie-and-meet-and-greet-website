@extends('layouts.admin')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods Management')

@section('page-actions')
    <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Payment Method
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @if($paymentMethods->count() > 0)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Payment Methods ({{ $paymentMethods->count() }})
                    </h5>
                    <div class="card-tools">
                        <span class="badge bg-info">Drag to reorder</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50"><i class="fas fa-grip-vertical"></i></th>
                                    <th>Payment Method</th>
                                    <th>Processing Fee</th>
                                    <th>Limits</th>
                                    <th>Status</th>
                                    <th>Bookings</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="payment-methods-container" class="sortable">
                                @foreach($paymentMethods as $paymentMethod)
                                    <tr class="sortable-item" data-payment-method-id="{{ $paymentMethod->id }}">
                                        <td class="text-center">
                                            <div class="drag-handle" style="cursor: move; padding: 10px;">
                                                <i class="fas fa-grip-vertical text-muted"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    {!! $paymentMethod->icon_html !!}
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $paymentMethod->name }}</h6>
                                                    <small class="text-muted">{{ $paymentMethod->slug }}</small>
                                                    @if($paymentMethod->description)
                                                        <br><small class="text-info">{{ Str::limit($paymentMethod->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $paymentMethod->formatted_processing_fee }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $paymentMethod->amount_limits_text }}</small>
                                        </td>
                                        <td>
                                            @if($paymentMethod->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $paymentMethod->bookings_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.payment-methods.show', $paymentMethod) }}" 
                                                   class="btn btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" 
                                                   class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- AJAX Toggle Button -->
                                                <button type="button" 
                                                        class="btn btn-outline-{{ $paymentMethod->is_active ? 'secondary' : 'success' }} toggle-status" 
                                                        data-payment-method-id="{{ $paymentMethod->id }}"
                                                        title="{{ $paymentMethod->is_active ? 'Deactivate' : 'Activate' }} (AJAX)">
                                                    <i class="fas fa-{{ $paymentMethod->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <!-- Backup Form Toggle -->
                                                <form method="POST" 
                                                      action="{{ route('admin.payment-methods.toggle-status', $paymentMethod) }}" 
                                                      style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-warning btn-sm ms-1" 
                                                            title="Toggle Status (Form Backup)" 
                                                            onclick="return confirm('Toggle {{ $paymentMethod->name }} status?')">
                                                        <i class="fas fa-sync"></i>
                                                    </button>
                                                </form>
                                                <button type="button" 
                                                        class="btn btn-outline-danger delete-payment-method" 
                                                        data-payment-method-id="{{ $paymentMethod->id }}"
                                                        data-payment-method-name="{{ $paymentMethod->name }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                    <h4>No Payment Methods Found</h4>
                    <p class="text-muted">Add your first payment method to start accepting payments.</p>
                    <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Payment Method
                    </a>
                </div>
            </div>
        @endif
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
                <p>Are you sure you want to delete the payment method "<strong id="payment-method-name-to-delete"></strong>"?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone. Payment methods used in existing bookings cannot be deleted.
                </p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Payment Method</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<style>
    .sortable-item {
        cursor: move;
    }
    
    .ui-sortable-placeholder {
        height: 60px;
        background: rgba(255, 215, 0, 0.1);
        border: 2px dashed var(--admin-accent);
        visibility: visible !important;
    }
    
    .payment-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }
    
    .drag-handle:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize sortable
    $("#payment-methods-container").sortable({
        items: ".sortable-item",
        handle: ".drag-handle",
        placeholder: "ui-sortable-placeholder",
        update: function(event, ui) {
            var paymentMethodIds = [];
            $(this).children('.sortable-item').each(function() {
                paymentMethodIds.push($(this).data('payment-method-id'));
            });
            
            // Update order via AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.post('{{ route("admin.payment-methods.update-order") }}', {
                payment_method_ids: paymentMethodIds
            })
            .done(function(response) {
                if (response.success) {
                    showToast('success', response.message);
                }
            })
            .fail(function() {
                showToast('error', 'Failed to update payment method order');
            });
        }
    });
    
    // Toggle status
    $('.toggle-status').click(function(e) {
        e.preventDefault();
        
        var paymentMethodId = $(this).data('payment-method-id');
        var button = $(this);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Debug logs
        console.log('Toggle clicked for payment method ID:', paymentMethodId);
        console.log('CSRF Token:', csrfToken);
        console.log('Button element:', button);
        
        // Disable button during request
        button.prop('disabled', true);
        
        // Construct the full URL properly
        var toggleUrl = '{{ route("admin.payment-methods.index") }}' + '/' + paymentMethodId + '/toggle-status';
        console.log('Toggle URL:', toggleUrl);
        
        $.ajax({
            url: toggleUrl,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            dataType: 'json',
            timeout: 10000, // 10 second timeout
            success: function(response) {
                console.log('Toggle success response:', response);
                
                if (response.success) {
                    // Update button appearance
                    if (response.is_active) {
                        button.removeClass('btn-outline-success').addClass('btn-outline-secondary');
                        button.find('i').removeClass('fa-play').addClass('fa-pause');
                        button.attr('title', 'Deactivate');
                        // Update status badge
                        var statusBadge = button.closest('tr').find('td:nth-child(5) .badge');
                        statusBadge.removeClass('bg-secondary').addClass('bg-success').text('Active');
                    } else {
                        button.removeClass('btn-outline-secondary').addClass('btn-outline-success');
                        button.find('i').removeClass('fa-pause').addClass('fa-play');
                        button.attr('title', 'Activate');
                        // Update status badge
                        var statusBadge = button.closest('tr').find('td:nth-child(5) .badge');
                        statusBadge.removeClass('bg-success').addClass('bg-secondary').text('Inactive');
                    }
                    
                    showToast('success', response.message);
                } else {
                    console.error('Server returned success=false:', response);
                    showToast('error', response.message || 'Server returned an error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error Details:');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Status Code:', xhr.status);
                console.error('Ready State:', xhr.readyState);
                
                var errorMessage = 'Failed to update payment method status';
                
                if (xhr.status === 0) {
                    errorMessage += ': Network error or request was cancelled';
                } else if (xhr.status === 404) {
                    errorMessage += ': Route not found (404)';
                } else if (xhr.status === 403) {
                    errorMessage += ': Permission denied (403)';
                } else if (xhr.status === 419) {
                    errorMessage += ': CSRF token mismatch (419) - please refresh the page';
                } else if (xhr.status === 500) {
                    errorMessage += ': Server error (500)';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += ': ' + xhr.responseJSON.message;
                } else {
                    errorMessage += ': ' + error;
                }
                
                showToast('error', errorMessage);
            },
            complete: function() {
                // Re-enable button
                button.prop('disabled', false);
            }
        });
    });
    
    // Delete payment method
    $('.delete-payment-method').click(function() {
        var paymentMethodId = $(this).data('payment-method-id');
        var paymentMethodName = $(this).data('payment-method-name');
        
        $('#payment-method-name-to-delete').text(paymentMethodName);
        $('#delete-form').attr('action', '/admin/payment-methods/' + paymentMethodId);
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
