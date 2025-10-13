@extends('layouts.admin')

@section('title', 'Edit Image')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Image
                </h1>
                <div class="btn-group">
                    <a href="{{ route('admin.images.show', $image) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>
                        View Image
                    </a>
                    <a href="{{ route('admin.images.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Images
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Edit Form -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cog me-2"></i>
                                Image Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.images.update', $image) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Current Image -->
                                <div class="mb-4">
                                    <label class="form-label">Current Image</label>
                                    <div class="border rounded p-3 text-center">
                                        <img src="{{ $image->url }}" 
                                             alt="{{ $image->alt_text }}" 
                                             class="img-fluid" 
                                             style="max-height: 300px;">
                                    </div>
                                    <div class="text-muted small mt-2">
                                        <strong>Filename:</strong> {{ $image->filename }}<br>
                                        <strong>Original:</strong> {{ $image->original_name }}<br>
                                        <strong>Size:</strong> {{ $image->formatted_size }}<br>
                                        @if($image->dimensions)
                                            <strong>Dimensions:</strong> {{ $image->dimensions }}<br>
                                        @endif
                                        <strong>Type:</strong> {{ $image->mime_type }}
                                    </div>
                                </div>

                                <!-- Image Type -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                        <option value="main" {{ old('type', $image->type) == 'main' ? 'selected' : '' }}>Main Image</option>
                                        <option value="gallery" {{ old('type', $image->type) == 'gallery' ? 'selected' : '' }}>Gallery Image</option>
                                        <option value="thumbnail" {{ old('type', $image->type) == 'thumbnail' ? 'selected' : '' }}>Thumbnail</option>
                                        <option value="banner" {{ old('type', $image->type) == 'banner' ? 'selected' : '' }}>Banner</option>
                                        <option value="background" {{ old('type', $image->type) == 'background' ? 'selected' : '' }}>Background</option>
                                        <option value="logo" {{ old('type', $image->type) == 'logo' ? 'selected' : '' }}>Logo</option>
                                        <option value="other" {{ old('type', $image->type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alt Text -->
                                <div class="mb-3">
                                    <label for="alt_text" class="form-label">Alt Text</label>
                                    <input type="text" 
                                           class="form-control @error('alt_text') is-invalid @enderror" 
                                           id="alt_text" 
                                           name="alt_text" 
                                           value="{{ old('alt_text', $image->alt_text) }}"
                                           placeholder="Describe the image for accessibility">
                                    @error('alt_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Alt text is important for accessibility and SEO.
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $image->title) }}"
                                           placeholder="Image title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              placeholder="Additional description or notes about the image">{{ old('description', $image->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', $image->sort_order) }}"
                                           min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Lower numbers appear first. Default is 0.
                                    </div>
                                </div>

                                <!-- Status Options -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_featured" 
                                                   name="is_featured" 
                                                   value="1"
                                                   {{ old('is_featured', $image->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                <i class="fas fa-star me-1"></i>
                                                Featured Image
                                            </label>
                                        </div>
                                        <small class="text-muted">Mark this image as featured</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1"
                                                   {{ old('is_active', $image->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <i class="fas fa-eye me-1"></i>
                                                Active
                                            </label>
                                        </div>
                                        <small class="text-muted">Make this image visible</small>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Update Image
                                    </button>
                                    <a href="{{ route('admin.images.show', $image) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye me-2"></i>
                                        View Image
                                    </a>
                                    <a href="{{ route('admin.images.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image Information -->
                <div class="col-lg-4">
                    <!-- File Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                File Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">ID:</th>
                                    <td>{{ $image->id }}</td>
                                </tr>
                                <tr>
                                    <th>Filename:</th>
                                    <td>{{ $image->filename }}</td>
                                </tr>
                                <tr>
                                    <th>Original Name:</th>
                                    <td>{{ $image->original_name }}</td>
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
                                    <th>Disk:</th>
                                    <td>{{ $image->disk }}</td>
                                </tr>
                                <tr>
                                    <th>Path:</th>
                                    <td><small>{{ $image->path }}</small></td>
                                </tr>
                            </table>
                        </div>
                    </div>

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
                            <p><strong>Used by:</strong> {{ class_basename($image->imageable_type) }}</p>
                            <p><strong>Model ID:</strong> {{ $image->imageable_id }}</p>
                            @if(method_exists($image->imageable, 'name'))
                                <p><strong>Name:</strong> {{ $image->imageable->name }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Timestamps
                            </h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Created:</strong><br>{{ $image->created_at->format('M j, Y g:i A') }}</p>
                            <p><strong>Updated:</strong><br>{{ $image->updated_at->format('M j, Y g:i A') }}</p>
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
