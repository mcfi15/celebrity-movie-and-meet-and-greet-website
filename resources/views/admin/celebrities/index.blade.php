@extends('layouts.admin')

@section('title', 'Celebrities')
@section('page-title', 'Manage Celebrities')

@section('page-actions')
    <a href="{{ route('admin.celebrities.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Add New Celebrity
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            Celebrities List
        </h5>
    </div>
    <div class="card-body">
        @if($celebrities->count() > 0)
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Hourly Rate</th>
                            <th>Status</th>
                            <th>Services</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($celebrities as $celebrity)
                        <tr>
                            <td>#{{ $celebrity->id }}</td>
                            <td>
                                @if($celebrity->image)
                                    <img src="{{ asset('storage/' . $celebrity->image) }}" 
                                         alt="{{ $celebrity->name }}" 
                                         class="rounded-circle" 
                                         width="50" 
                                         height="50" 
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $celebrity->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $celebrity->profession }}</small>
                            </td>
                            <td>{{ $celebrity->category }}</td>
                            <td>${{ number_format($celebrity->hourly_rate, 2) }}</td>
                            <td>
                                @if($celebrity->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $celebrity->services->count() }} services</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.celebrities.show', $celebrity) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.celebrities.edit', $celebrity) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.celebrities.destroy', $celebrity) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirmDelete(this)">
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $celebrities->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>No Celebrities Found</h5>
                <p>Start by adding your first celebrity to the system.</p>
                <a href="{{ route('admin.celebrities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add First Celebrity
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(form) {
        if (confirm('Are you sure you want to delete this celebrity? This will also delete all associated bookings and cannot be undone.')) {
            form.submit();
        }
        return false;
    }
</script>
@endpush