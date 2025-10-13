@extends('layouts.admin')

@section('title', 'Service Types')
@section('page-title', 'Manage Service Types')

@section('page-actions')
    <a href="{{ route('admin.service-types.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Add New Service Type
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Service Types List
        </h5>
    </div>
    <div class="card-body">
        @if($serviceTypes->count() > 0)
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Celebrities</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serviceTypes as $serviceType)
                        <tr>
                            <td>#{{ $serviceType->id }}</td>
                            <td><strong>{{ $serviceType->name }}</strong></td>
                            <td>{{ Str::limit($serviceType->description, 50) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $serviceType->celebrityServices->count() }} celebrities</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $serviceType->bookings->count() }} bookings</span>
                            </td>
                            <td>
                                @if($serviceType->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.service-types.show', $serviceType) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.service-types.edit', $serviceType) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.service-types.destroy', $serviceType) }}" 
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
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-list fa-3x mb-3"></i>
                <h5>No Service Types Found</h5>
                <p>Start by adding your first service type to the system.</p>
                <a href="{{ route('admin.service-types.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add First Service Type
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this service type? This action cannot be undone.');
    }
</script>
@endpush