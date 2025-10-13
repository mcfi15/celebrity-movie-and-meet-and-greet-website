@extends('layouts.admin')

@section('title', 'View Image')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-image me-2"></i>
                    Image Details
                </h1>
                <div class="btn-group">
                    <a href="{{ route('admin.images.edit', $image) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Image
                    </a>
                    <a href="{{ route('admin.images.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Images
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Image Display -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-picture-o me-2"></i>
                                {{ $image->original_name }}
                            </h5>
                            <div class="btn-group">
                                <a href="{{ $image->url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Open Full Size
                                </a>
                                <a href="{{ $image->url }}" download="{{ $image->original_name }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $image->url }}" 
                                 alt="{{ $image->alt_text }}" 
                                 class="img-fluid border rounded"
                                 style="max-height: 500px;">
                        </div>
                    </div>

                    <!-- Image Metadata -->
                    @if($image->alt_text || $image->title || $image->description)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tags me-2"></i>
                                Image Metadata
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($image->title)
                                <div class="mb-3">
                                    <strong>Title:</strong>
                                    <p class="mb-0">{{ $image->title }}</p>
                                </div>
                            @endif

                            @if($image->alt_text)
                                <div class="mb-3">
                                    <strong>Alt Text:</strong>
                                    <p class="mb-0">{{ $image->alt_text }}</p>
                                </div>
                            @endif

                            @if($image->description)
                                <div class="mb-0">
                                    <strong>Description:</strong>
                                    <p class="mb-0">{{ $image->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Usage Information -->
                    @if($image->imageable)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-link me-2"></i>
                                Usage Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Used by:</strong><br>
                                    <span class="badge bg-info">{{ class_basename($image->imageable_type) }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Model ID:</strong><br>
                                    {{ $image->imageable_id }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Image Type:</strong><br>
                                    <span class="badge bg-secondary">{{ ucfirst($image->type) }}</span>
                                </div>
                            </div>

                            @if(method_exists($image->imageable, 'name'))
                                <div class="mt-3">
                                    <strong>Related Item:</strong><br>
                                    {{ $image->imageable->name }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Image Information Sidebar -->
                <div class="col-lg-4">
                    <!-- Status & Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cog me-2"></i>
                                Status & Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Status Badges -->
                            <div class="mb-3">
                                @if($image->is_active)
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-check me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary me-2">
                                        <i class="fas fa-pause me-1"></i>
                                        Inactive
                                    </span>
                                @endif

                                @if($image->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i>
                                        Featured
                                    </span>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.images.edit', $image) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Image
                                </a>
                                <a href="{{ $image->url }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    View Full Size
                                </a>
                                <a href="{{ $image->url }}" download="{{ $image->original_name }}" class="btn btn-outline-success">
                                    <i class="fas fa-download me-2"></i>
                                    Download Image
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- File Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                File Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="45%">ID:</th>
                                    <td>{{ $image->id }}</td>
                                </tr>
                                <tr>
                                    <th>Filename:</th>
                                    <td><small>{{ $image->filename }}</small></td>
                                </tr>
                                <tr>
                                    <th>Original Name:</th>
                                    <td><small>{{ $image->original_name }}</small></td>
                                </tr>
                                <tr>
                                    <th>MIME Type:</th>
                                    <td>{{ $image->mime_type }}</td>
                                </tr>
                                <tr>
                                    <th>File Size:</th>
                                    <td>{{ $image->formatted_size }}</td>
                                </tr>
                                @if($image->dimensions)
                                <tr>
                                    <th>Dimensions:</th>
                                    <td>{{ $image->dimensions }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Storage Disk:</th>
                                    <td>{{ $image->disk }}</td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $image->sort_order }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Timestamps
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Created:</strong><br>
                                <small>{{ $image->created_at->format('M j, Y g:i A') }}</small><br>
                                <small class="text-muted">{{ $image->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                <strong>Last Updated:</strong><br>
                                <small>{{ $image->updated_at->format('M j, Y g:i A') }}</small><br>
                                <small class="text-muted">{{ $image->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Storage Path -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-folder me-2"></i>
                                Storage Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Storage Path:</strong><br>
                                <small class="text-muted font-monospace">{{ $image->path }}</small>
                            </div>
                            <div class="mb-2">
                                <strong>Public URL:</strong><br>
                                <a href="{{ $image->url }}" target="_blank" class="small text-break">
                                    {{ $image->url }}
                                </a>
                            </div>
                            <div>
                                <strong>File Exists:</strong>
                                @if($image->exists())
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>
                                        Yes
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>
                                        No
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card mt-4 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Danger Zone
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-danger mb-3">
                                <strong>Warning:</strong> This action cannot be undone. The image file will be permanently deleted.
                            </p>
                            <form action="{{ route('admin.images.destroy', $image) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you absolutely sure you want to delete this image? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>
                                    Delete Image
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
