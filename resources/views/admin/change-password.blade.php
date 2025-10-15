@extends('layouts.admin')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lock me-2"></i>
                    Change Your Password
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.change-password.update') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-key me-1"></i>
                            Current Password
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-lock me-1"></i>
                            New Password
                        </label>
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" 
                               name="new_password" 
                               required 
                               minlength="8">
                        <div class="form-text text-white">Password must be at least 8 characters long</div>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">
                            <i class="fas fa-lock me-1"></i>
                            Confirm New Password
                        </label>
                        <input type="password" 
                               class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               required 
                               minlength="8">
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Password
                        </button>
                        <a href="{{ url('admin/dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Password Security Tips
                </h6>
            </div>
            <div class="card-body text-white">
                <div class="mb-3">
                    <h6>Strong Password Guidelines:</h6>
                    <ul class="small">
                        <li>Use at least 8 characters</li>
                        <li>Include uppercase and lowercase letters</li>
                        <li>Add numbers and special characters</li>
                        <li>Avoid common words or personal information</li>
                        <li>Don't reuse old passwords</li>
                    </ul>
                </div>
                
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Security Note:</strong> Your new password will be encrypted and stored securely. Make sure to remember it as passwords cannot be recovered.
                </div>
                
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>Important:</strong> After changing your password, you may need to log in again on other devices.
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Account Information
                </h6>
            </div>
            <div class="card-body text-white">
                <div class="mb-2">
                    <strong>Name:</strong> {{ Auth::user()->name }}
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> {{ Auth::user()->email }}
                </div>
                <div class="mb-2">
                    <strong>Role:</strong> Administrator
                </div>
                <div class="mb-2">
                    <strong>Last Login:</strong> 
                    @if(Auth::user()->last_login_at)
                        {{ Auth::user()->last_login_at->format('M d, Y H:i') }}
                    @else
                        Not available
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password strength indicator
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('password-strength');
        
        if (strengthIndicator) {
            strengthIndicator.remove();
        }
        
        if (password.length > 0) {
            let strength = 0;
            let strengthText = '';
            let strengthClass = '';
            
            // Check password criteria
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Determine strength level
            switch(strength) {
                case 0:
                case 1:
                    strengthText = 'Very Weak';
                    strengthClass = 'text-danger';
                    break;
                case 2:
                    strengthText = 'Weak';
                    strengthClass = 'text-warning';
                    break;
                case 3:
                    strengthText = 'Medium';
                    strengthClass = 'text-info';
                    break;
                case 4:
                    strengthText = 'Strong';
                    strengthClass = 'text-success';
                    break;
                case 5:
                    strengthText = 'Very Strong';
                    strengthClass = 'text-success fw-bold';
                    break;
            }
            
            // Create strength indicator
            const indicator = document.createElement('div');
            indicator.id = 'password-strength';
            indicator.className = `form-text ${strengthClass}`;
            indicator.innerHTML = `<i class="fas fa-shield-alt me-1"></i>Password Strength: ${strengthText}`;
            
            this.parentNode.appendChild(indicator);
        }
    });

    // Password confirmation validation
    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirmation = this.value;
        const matchIndicator = document.getElementById('password-match');
        
        if (matchIndicator) {
            matchIndicator.remove();
        }
        
        if (confirmation.length > 0) {
            const indicator = document.createElement('div');
            indicator.id = 'password-match';
            indicator.className = 'form-text';
            
            if (password === confirmation) {
                indicator.className += ' text-success';
                indicator.innerHTML = '<i class="fas fa-check me-1"></i>Passwords match';
            } else {
                indicator.className += ' text-danger';
                indicator.innerHTML = '<i class="fas fa-times me-1"></i>Passwords do not match';
            }
            
            this.parentNode.appendChild(indicator);
        }
    });
</script>
@endpush
