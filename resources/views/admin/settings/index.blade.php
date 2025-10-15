@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- General Settings -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        General Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name *</label>
                                <input type="text" 
                                       class="form-control @error('site_name') is-invalid @enderror" 
                                       id="site_name" 
                                       name="site_name" 
                                       value="{{ old('site_name', $settings['site_name'] ?? 'Celebrity Agency') }}" 
                                       required>
                                @error('site_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_tagline" class="form-label">Site Tagline</label>
                                <input type="text" 
                                       class="form-control @error('site_tagline') is-invalid @enderror" 
                                       id="site_tagline" 
                                       name="site_tagline" 
                                       value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}" 
                                       placeholder="Your celebrity connection starts here">
                                @error('site_tagline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                  id="site_description" 
                                  name="site_description" 
                                  rows="3" 
                                  placeholder="Brief description of your celebrity agency...">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email *</label>
                                <input type="email" 
                                       class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" 
                                       name="contact_email" 
                                       value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" 
                                       required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" 
                                       class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" 
                                       name="contact_phone" 
                                       value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" 
                                       placeholder="+1 (555) 123-4567">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact_address" class="form-label">Contact Address</label>
                        <textarea class="form-control @error('contact_address') is-invalid @enderror" 
                                  id="contact_address" 
                                  name="contact_address" 
                                  rows="2" 
                                  placeholder="123 Hollywood Blvd, Los Angeles, CA 90028">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        @error('contact_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_logo" class="form-label">Site Logo</label>
                        <input type="file" 
                               class="form-control @error('site_logo') is-invalid @enderror" 
                               id="site_logo" 
                               name="site_logo" 
                               accept="image/*">
                        <div class="form-text text-white">Upload a new logo to replace the current one. Recommended size: 200x50px</div>
                        @error('site_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="site_favicon" class="form-label">Site Favicon</label>
                        <input type="file" 
                               class="form-control @error('site_favicon') is-invalid @enderror" 
                               id="site_favicon" 
                               name="site_favicon" 
                               accept="image/*">
                        <div class="form-text text-white">Upload a new favicon to replace the current one. Recommended size: 200x50px</div>
                        @error('site_favicon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Payment Settings -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Payment Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="payment_enabled" 
                                   name="payment_enabled" 
                                   value="1" 
                                   {{ old('payment_enabled', $settings['payment_enabled'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="payment_enabled">
                                <strong>Enable Payments</strong>
                                <br><small class="text-muted">Allow customers to make payments when booking</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="stripe_enabled" 
                                       name="stripe_enabled" 
                                       value="1" 
                                       {{ old('stripe_enabled', $settings['stripe_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="stripe_enabled">
                                    Credit Cards (Stripe)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="crypto_enabled" 
                                       name="crypto_enabled" 
                                       value="1" 
                                       {{ old('crypto_enabled', $settings['crypto_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="crypto_enabled">
                                    Cryptocurrency
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="bank_transfer_enabled" 
                                       name="bank_transfer_enabled" 
                                       value="1" 
                                       {{ old('bank_transfer_enabled', $settings['bank_transfer_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="bank_transfer_enabled">
                                    Bank Transfer
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Payment Methods -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="paypal_enabled" 
                                       name="paypal_enabled" 
                                       value="1" 
                                       {{ old('paypal_enabled', $settings['paypal_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="paypal_enabled">
                                    PayPal
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="cash_enabled" 
                                       name="cash_enabled" 
                                       value="1" 
                                       {{ old('cash_enabled', $settings['cash_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="cash_enabled">
                                    Cash Payment
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Logo & Quick Stats -->
        <div class="col-md-4">
            @if(isset($settings['site_logo']) && $settings['site_logo'])
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>
                            Current Logo
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset($settings['site_logo']) }}" 
                             alt="Site Logo" 
                             class="img-fluid" 
                             style="max-height: 100px;">
                    </div>
                </div>
            @endif

            <br>
        
            @if(isset($settings['site_favicon']) && $settings['site_favicon'])
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-image me-2"></i>
                            Current Favicon
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset($settings['site_favicon']) }}" 
                             alt="Site Favicon" 
                             class="img-fluid" 
                             style="max-height: 100px;">
                    </div>
                </div>
            @endif
            
            <!-- Quick Stats -->
            <div class="card {{ isset($settings['site_logo']) && $settings['site_logo'] ? 'mt-4' : '' }}">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Site Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-warning">{{ $stats['celebrities'] ?? 0 }}</h4>
                            <small class="text-muted">Celebrities</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-info">{{ $stats['bookings'] ?? 0 }}</h4>
                            <small class="text-muted">Bookings</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $stats['services'] ?? 0 }}</h4>
                            <small class="text-muted">Services</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-primary">{{ $stats['messages'] ?? 0 }}</h4>
                            <small class="text-muted">Messages</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>
                    Save Settings
                </button>
            </div>
        </div>
    </div>
</form>
@endsection