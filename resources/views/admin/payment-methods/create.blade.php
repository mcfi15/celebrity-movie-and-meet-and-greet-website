@extends('layouts.admin')

@section('title', 'Add Payment Method')
@section('page-title', 'Add New Payment Method')

@section('page-actions')
    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
@endsection

@section('content')
<form action="{{ route('admin.payment-methods.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Payment Method Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}"
                                       placeholder="Auto-generated if empty">
                                <div class="form-text text-white">Used in URLs. Leave empty to auto-generate.</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description of this payment method">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon') }}"
                                       placeholder="e.g., fas fa-credit-card or image URL">
                                <div class="form-text text-white">Font Awesome class (e.g., 'fas fa-credit-card') or image URL</div>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#007bff') }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fee Structure -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-percent me-2"></i>
                        Processing Fees
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="processing_fee_percentage" class="form-label">Percentage Fee (%)</label>
                                <input type="number" class="form-control @error('processing_fee_percentage') is-invalid @enderror" 
                                       id="processing_fee_percentage" name="processing_fee_percentage" 
                                       value="{{ old('processing_fee_percentage', 0) }}" 
                                       step="0.01" min="0" max="100">
                                <div class="form-text text-white">Percentage of transaction amount (e.g., 2.9 for 2.9%)</div>
                                @error('processing_fee_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="processing_fee_fixed" class="form-label">Fixed Fee ($)</label>
                                <input type="number" class="form-control @error('processing_fee_fixed') is-invalid @enderror" 
                                       id="processing_fee_fixed" name="processing_fee_fixed" 
                                       value="{{ old('processing_fee_fixed', 0) }}" 
                                       step="0.01" min="0">
                                <div class="form-text text-white">Fixed amount per transaction (e.g., 0.30 for $0.30)</div>
                                @error('processing_fee_fixed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Amount Limits -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Amount Limits
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="minimum_amount" class="form-label">Minimum Amount ($)</label>
                                <input type="number" class="form-control @error('minimum_amount') is-invalid @enderror" 
                                       id="minimum_amount" name="minimum_amount" 
                                       value="{{ old('minimum_amount') }}" 
                                       step="0.01" min="0">
                                <div class="form-text text-white">Leave empty for no minimum</div>
                                @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maximum_amount" class="form-label">Maximum Amount ($)</label>
                                <input type="number" class="form-control @error('maximum_amount') is-invalid @enderror" 
                                       id="maximum_amount" name="maximum_amount" 
                                       value="{{ old('maximum_amount') }}" 
                                       step="0.01" min="0">
                                <div class="form-text text-white">Leave empty for no maximum</div>
                                @error('maximum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Settings & Configuration -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="is_active">
                                <strong>Active</strong>
                                <br><small class="text-unmute">Allow customers to use this payment method</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Instructions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info me-2"></i>
                        Instructions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Customer Instructions</label>
                        <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                  id="instructions" name="instructions" rows="4" 
                                  placeholder="Instructions shown to customers when they select this payment method">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- API Configuration -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        API Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="api_key" class="form-label">API Key</label>
                        <input type="text" class="form-control @error('api_key') is-invalid @enderror" 
                               id="api_key" name="api_key" value="{{ old('api_key') }}">
                        @error('api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="api_secret" class="form-label">API Secret</label>
                        <input type="password" class="form-control @error('api_secret') is-invalid @enderror" 
                               id="api_secret" name="api_secret" value="{{ old('api_secret') }}">
                        @error('api_secret')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="webhook_url" class="form-label">Webhook URL</label>
                        <input type="url" class="form-control @error('webhook_url') is-invalid @enderror" 
                               id="webhook_url" name="webhook_url" value="{{ old('webhook_url') }}">
                        @error('webhook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Payment Method
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug from name
    $('#name').on('input', function() {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        $('#slug').val(slug);
    });
    
    // Preview icon
    $('#icon, #color').on('input', function() {
        updateIconPreview();
    });
    
    function updateIconPreview() {
        var icon = $('#icon').val();
        var color = $('#color').val();
        
        if (icon) {
            var preview = '';
            if (icon.startsWith('fa')) {
                preview = '<i class="' + icon + '" style="color: ' + color + '; font-size: 24px;"></i>';
            } else if (icon.startsWith('http')) {
                preview = '<img src="' + icon + '" style="width: 24px; height: 24px;" alt="Icon">';
            }
            
            if (!$('#icon-preview').length) {
                $('#icon').after('<div id="icon-preview" class="mt-2"></div>');
            }
            $('#icon-preview').html('<strong>Preview:</strong> ' + preview);
        }
    }
    
    // Fee calculator
    $('#processing_fee_percentage, #processing_fee_fixed').on('input', function() {
        updateFeePreview();
    });
    
    function updateFeePreview() {
        var percentage = parseFloat($('#processing_fee_percentage').val()) || 0;
        var fixed = parseFloat($('#processing_fee_fixed').val()) || 0;
        
        var testAmount = 100;
        var percentageFee = (testAmount * percentage) / 100;
        var totalFee = percentageFee + fixed;
        
        if (!$('#fee-preview').length) {
            $('#processing_fee_fixed').after('<div id="fee-preview" class="mt-2 text-info"></div>');
        }
        
        if (percentage > 0 || fixed > 0) {
            $('#fee-preview').html('<small><strong>Example:</strong> $' + testAmount + ' transaction = $' + totalFee.toFixed(2) + ' fee</small>');
        } else {
            $('#fee-preview').html('');
        }
    }
});
</script>
@endpush
