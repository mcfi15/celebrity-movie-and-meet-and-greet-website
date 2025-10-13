@extends('layouts.admin')

@section('title', 'Images')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-images me-2"></i>
                        Images Management
                    </h3>
                    <a href="{{ route('admin.images.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Upload Image
                    </a>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.images.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search images...">
                        </div>
                        <div class="col-md-2">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="main" {{ request('type') == 'main' ? 'selected' : '' }}>Main</option>
                                <option value="gallery" {{ request('type') == 'gallery' ? 'selected' : '' }}>Gallery</option>
                                <option value="thumbnail" {{ request('type') == 'thumbnail' ? 'selected' : '' }}>Thumbnail</option>
                                <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="model_type" class="form-label">Model</label>
                            <select class="form-select" id="model_type" name="model_type">
                                <option value="">All Models</option>
                                <option value="App\Models\Celebrity" {{ request('model_type') == 'App\Models\Celebrity' ? 'selected' : '' }}>Celebrity</option>
                                <option value="App\Models\Testimonial" {{ request('model_type') == 'App\Models\Testimonial' ? 'selected' : '' }}>Testimonial</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="active" class="form-label">Status</label>
                            <select class="form-select" id="active" name="active">
                                <option value="">All Status</option>
                                <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.images.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if($images->count() > 0)
                        <div class="row">
                            @foreach($images as $image)
                                <div class="col-md-3 col-sm-4 col-6 mb-4">
                                    <div class="card h-100 image-card">
                                        <div class="position-relative">
                                            <img src="{{ $image->url }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $image->alt_text }}"
                                                 style="height: 200px; object-fit: cover;">
                                            
                                            <!-- Status badges -->
                                            <div class="position-absolute top-0 start-0 p-2">
                                                @if($image->is_featured)
                                                    <span class="badge bg-warning">Featured</span>
                                                @endif
                                                @if(!$image->is_active)
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </div>

                                            <!-- Action buttons -->
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <div class="btn-group-vertical" role="group">
                                                    <a href="{{ route('admin.images.show', $image) }}" 
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.images.edit', $image) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.images.destroy', $image) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this image?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-1 text-truncate" title="{{ $image->original_name }}">
                                                {{ $image->original_name }}
                                            </h6>
                                            <div class="small text-muted">
                                                <div><strong>Type:</strong> {{ ucfirst($image->type) }}</div>
                                                <div><strong>Size:</strong> {{ $image->formatted_size }}</div>
                                                @if($image->dimensions)
                                                    <div><strong>Dimensions:</strong> {{ $image->dimensions }}</div>
                                                @endif
                                                @if($image->imageable)
                                                    <div><strong>Used by:</strong> {{ class_basename($image->imageable_type) }}</div>
                                                @endif
                                                <div><strong>Uploaded:</strong> {{ $image->created_at->format('M j, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $images->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No images found</h5>
                            <p class="text-muted">Upload your first image to get started.</p>
                            <a href="{{ route('admin.images.create') }}" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>
                                Upload Image
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-card {
    transition: transform 0.2s;
}

.image-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-group-vertical .btn {
    border-radius: 0.25rem !important;
    margin-bottom: 2px;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}
</style>
@endsection
