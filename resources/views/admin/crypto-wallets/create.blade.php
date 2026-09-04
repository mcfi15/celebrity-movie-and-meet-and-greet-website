@extends('layouts.admin')

@section('title', 'Add Crypto Wallet')
@section('page-title', 'Add New Crypto Wallet')

@section('page-actions')
    <a href="{{ route('admin.crypto-wallets.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
@endsection

@section('content')
<form action="{{ route('admin.crypto-wallets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Wallet Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Payment Method Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="e.g., Bitcoin (BTC - SegWit)">
                                <div class="form-text text-white">Display name for this network/wallet.</div>
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
                                <div class="form-text text-white">Used internally. Leave empty to auto-generate.</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="wallet_address" class="form-label">Wallet Address *</label>
                        <textarea class="form-control @error('wallet_address') is-invalid @enderror"
                                  id="wallet_address" name="wallet_address" rows="3"
                                  placeholder="e.g., 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa"
                                  required>{{ old('wallet_address') }}</textarea>
                        <div class="form-text text-white">The deposit address customers send funds to.</div>
                        @error('wallet_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="instructions" class="form-label">Network / Method Description & Instructions</label>
                        <textarea class="form-control @error('instructions') is-invalid @enderror"
                                  id="instructions" name="instructions" rows="4"
                                  placeholder="e.g., Send only USDT via TRC20 network. Minimum deposit $50.">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Uploads & Settings -->
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
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="is_active">
                                <strong>Active</strong>
                                <br><small class="text-muted">Show this wallet at checkout</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-image me-2"></i>
                        Wallet Image / Branding Logo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="file" class="form-control @error('wallet_image') is-invalid @enderror"
                               id="wallet_image" name="wallet_image" accept="image/png,image/jpeg,image/webp"
                               onchange="previewImage(this, 'wallet-image-preview')">
                        <div class="form-text text-white">PNG, JPEG, WEBP. Max 2MB.</div>
                        @error('wallet_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="wallet-image-preview" class="text-center mt-2"></div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>
                        QR Code Image
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="file" class="form-control @error('qr_code_image') is-invalid @enderror"
                               id="qr_code_image" name="qr_code_image" accept="image/png,image/jpeg,image/webp"
                               onchange="previewImage(this, 'qr-image-preview')">
                        <div class="form-text text-white">QR code that scans to the deposit address. PNG, JPEG, WEBP. Max 2MB.</div>
                        @error('qr_code_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="qr-image-preview" class="text-center mt-2"></div>
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
                        <a href="{{ route('admin.crypto-wallets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Crypto Wallet
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
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('name').addEventListener('input', function() {
        var slug = this.value.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        if (!document.getElementById('slug').value || document.getElementById('slug').getAttribute('data-auto') !== 'manual') {
            document.getElementById('slug').value = slug;
        }
        document.getElementById('slug').setAttribute('data-auto', 'manual');
    });

    window.previewImage = function(input, previewId) {
        var preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" ' +
                    'style="max-height: 180px; max-width: 100%; border-radius: 8px; border: 1px solid #404040;">';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = '';
        }
    };
});
</script>
@endpush