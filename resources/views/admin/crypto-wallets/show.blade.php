@extends('layouts.admin')

@section('title', 'Crypto Wallet Details')
@section('page-title', 'Crypto Wallet: ' . $cryptoWallet->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.crypto-wallets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
        <a href="{{ route('admin.crypto-wallets.edit', $cryptoWallet) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <button type="button" class="btn btn-{{ $cryptoWallet->is_active ? 'outline-secondary' : 'outline-success' }} toggle-status"
                data-wallet-id="{{ $cryptoWallet->id }}">
            <i class="fas fa-{{ $cryptoWallet->is_active ? 'pause' : 'play' }} me-2"></i>
            {{ $cryptoWallet->is_active ? 'Deactivate' : 'Activate' }}
        </button>
        @if($bookingsCount == 0)
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Status Overview -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        @if($cryptoWallet->wallet_image)
                            <img src="{{ $cryptoWallet->wallet_image_url }}" alt="{{ $cryptoWallet->name }}"
                                 style="max-height: 70px; max-width: 160px; object-fit: contain; background: #fff; border-radius: 8px; padding: 4px;">
                        @else
                            <div style="font-size: 48px;">
                                <i class="fas fa-coins text-warning"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1">
                            @if($cryptoWallet->is_active)
                                <span class="badge bg-success fs-4">Active</span>
                            @else
                                <span class="badge bg-secondary fs-4">Inactive</span>
                            @endif
                        </h2>
                        <p class="text-muted">Status</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-info">{{ $bookingsCount }}</h2>
                        <p class="text-muted">Total Bookings</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-success">${{ number_format($totalRevenue, 2) }}</h2>
                        <p class="text-muted">Revenue Generated</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Basic Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Wallet Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Name:</strong></td>
                        <td>{{ $cryptoWallet->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug:</strong></td>
                        <td><code>{{ $cryptoWallet->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Wallet Address:</strong></td>
                        <td><code class="text-info" style="word-break: break-all;">{{ $cryptoWallet->wallet_address }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Sort Order:</strong></td>
                        <td><span class="badge bg-secondary">{{ $cryptoWallet->sort_order }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $cryptoWallet->created_at->format('M d, Y \a\t h:i A') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $cryptoWallet->updated_at->format('M d, Y \a\t h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($cryptoWallet->instructions)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info me-2"></i>
                        Network / Method Instructions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-dark rounded border">
                        {!! nl2br(e($cryptoWallet->instructions)) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Media -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-image me-2"></i>
                    Wallet Image / Branding Logo
                </h5>
            </div>
            <div class="card-body text-center">
                @if($cryptoWallet->wallet_image)
                    <img src="{{ $cryptoWallet->wallet_image_url }}" alt="Wallet image"
                         style="max-height: 200px; max-width: 100%; border-radius: 8px; border: 1px solid #404040; background: #fff;">
                @else
                    <p class="text-muted mb-0">No branding image uploaded.</p>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-qrcode me-2"></i>
                    QR Code
                </h5>
            </div>
            <div class="card-body text-center">
                @if($cryptoWallet->qr_code_image)
                    <img src="{{ $cryptoWallet->qr_code_image_url }}" alt="QR code"
                         style="max-height: 200px; max-width: 100%; border-radius: 8px; border: 1px solid #404040; background: #fff;">
                @else
                    <p class="text-muted mb-0">No QR code image uploaded.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
@if($bookingsCount > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Recent Bookings
                    </h5>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Bookings
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking #</th>
                                    <th>Customer</th>
                                    <th>Celebrity</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cryptoWallet->bookings()->with(['celebrity'])->latest()->limit(5)->get() as $booking)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-warning">
                                                {{ $booking->booking_number }}
                                            </a>
                                        </td>
                                        <td>{{ $booking->customer_name }}</td>
                                        <td>{{ $booking->celebrity->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($booking->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status_color }}">
                                                {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No bookings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the crypto wallet "<strong>{{ $cryptoWallet->name }}</strong>"?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone and will permanently remove the uploaded images.
                </p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.crypto-wallets.destroy', $cryptoWallet) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Crypto Wallet</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.toggle-status').addEventListener('click', function() {
        var button = this;
        button.disabled = true;
        fetch('{{ route("admin.crypto-wallets.index") }}/' + button.dataset.walletId + '/toggle-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update status');
                button.disabled = false;
            }
        })
        .catch(function() {
            alert('Failed to update status. Please try again.');
            button.disabled = false;
        });
    });
});
</script>
@endpush