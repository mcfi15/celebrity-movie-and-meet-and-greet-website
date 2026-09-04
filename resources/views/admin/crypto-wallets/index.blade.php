@extends('layouts.admin')

@section('title', 'Crypto Wallets')
@section('page-title', 'Crypto Wallet Management')

@section('page-actions')
    <a href="{{ route('admin.crypto-wallets.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Crypto Wallet
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @if($wallets->count() > 0)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-wallet me-2"></i>
                        Crypto Wallets ({{ $wallets->count() }})
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
                                    <th>Wallet</th>
                                    <th>Wallet Address</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
                                    <th>Bookings</th>
                                    <th width="190">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="crypto-wallets-container">
                                @foreach($wallets as $wallet)
                                    <tr class="sortable-item" data-wallet-id="{{ $wallet->id }}">
                                        <td class="text-center">
                                            <div class="drag-handle" style="cursor: move; padding: 10px;">
                                                <i class="fas fa-grip-vertical text-muted"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($wallet->wallet_image)
                                                    <img src="{{ $wallet->wallet_image_url }}" 
                                                         alt="{{ $wallet->name }}" 
                                                         class="me-3 rounded"
                                                         style="width: 40px; height: 40px; object-fit: contain; background: #fff;">
                                                @else
                                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-coins text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $wallet->name }}</h6>
                                                    <small class="text-muted">{{ $wallet->slug }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-info" style="word-break: break-all; max-width: 320px; display: inline-block;">
                                                {{ $wallet->wallet_address }}
                                            </code>
                                        </td>
                                        <td>
                                            @if($wallet->qr_code_image)
                                                <img src="{{ $wallet->qr_code_image_url }}" 
                                                     alt="QR Code" 
                                                     class="rounded bg-white"
                                                     style="width: 45px; height: 45px; object-fit: contain;">
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($wallet->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $wallet->bookings_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.crypto-wallets.show', $wallet) }}" 
                                                   class="btn btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.crypto-wallets.edit', $wallet) }}" 
                                                   class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-{{ $wallet->is_active ? 'secondary' : 'success' }} toggle-status" 
                                                        data-wallet-id="{{ $wallet->id }}"
                                                        title="{{ $wallet->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $wallet->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-danger delete-wallet" 
                                                        data-wallet-id="{{ $wallet->id }}"
                                                        data-wallet-name="{{ $wallet->name }}"
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
                    <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                    <h4>No Crypto Wallets Found</h4>
                    <p class="text-muted">Add your first crypto wallet to start accepting cryptocurrency payments.</p>
                    <a href="{{ route('admin.crypto-wallets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Crypto Wallet
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
                <p>Are you sure you want to delete the crypto wallet "<strong id="wallet-name-to-delete"></strong>"?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone and will permanently remove the uploaded images. Wallets used in existing bookings cannot be deleted.
                </p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Crypto Wallet</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .sortable-item { cursor: move; }

    .sortable-placeholder {
        height: 60px;
        background: rgba(255, 215, 0, 0.1);
        border: 2px dashed var(--admin-accent);
        visibility: visible !important;
    }

    .drag-handle:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    .toast-pill {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 280px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showToast(message, isError) {
        var toast = document.createElement('div');
        toast.className = 'alert alert-' + (isError ? 'danger' : 'success') +
            ' alert-dismissible fade show toast-pill';
        toast.innerHTML = '<i class="fas fa-' + (isError ? 'exclamation-triangle' : 'check-circle') +
            ' me-2"></i>' + message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(function() { toast.remove(); }, 500);
        }, 5000);
    }

    // Toggle status
    document.querySelectorAll('.toggle-status').forEach(function(button) {
        button.addEventListener('click', function() {
            button.disabled = true;
            fetch('{{ route("admin.crypto-wallets.index") }}/' + button.dataset.walletId + '/toggle-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast(data.message);
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    showToast(data.message || 'Failed to update status', true);
                    button.disabled = false;
                }
            })
            .catch(function() {
                showToast('Failed to update status. Please try again.', true);
                button.disabled = false;
            });
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-wallet').forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementById('wallet-name-to-delete').textContent = button.dataset.walletName;
            document.getElementById('delete-form').setAttribute('action',
                '{{ route("admin.crypto-wallets.index") }}/' + button.dataset.walletId);
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });

    // Drag-to-reorder using native HTML5 drag & drop
    const container = document.getElementById('crypto-wallets-container');
    let dragRow = null;

    container.querySelectorAll('.sortable-item').forEach(function(row) {
        row.addEventListener('dragstart', function(e) {
            dragRow = row;
            row.style.opacity = '0.4';
            e.dataTransfer.effectAllowed = 'move';
        });
        row.addEventListener('dragend', function() {
            row.style.opacity = '';
            dragRow = null;
            persistOrder();
        });
        row.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        row.addEventListener('drop', function(e) {
            e.preventDefault();
            if (dragRow && dragRow !== row) {
                container.insertBefore(dragRow, row);
            }
        });
    });

    function persistOrder() {
        const ids = Array.from(container.querySelectorAll('.sortable-item')).map(function(row) {
            return row.dataset.walletId;
        });

        fetch('{{ route("admin.crypto-wallets.update-order") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ crypto_wallet_ids: ids })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                showToast(data.message);
            }
        })
        .catch(function() {
            showToast('Failed to save wallet order. Please refresh the page.', true);
        });
    }
});
</script>
@endpush