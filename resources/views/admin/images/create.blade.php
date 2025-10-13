@extends('layouts.admin')

@section('title', 'Upload Image')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-upload me-2"></i>
                    Upload Image
                </h1>
                <a href="{{ route('admin.images.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Images
                </a>
            </div>

            <div class="row">
                <!-- Upload Form -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-upload me-2"></i>
                                Image Upload
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Image Upload -->
                                <div class="mb-4">
                                    <label for="image" class="form-label">
                                        Image <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Supported formats: JPEG, PNG, JPG, GIF, WebP. Maximum size: 10MB.
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="mb-4" id="imagePreview" style="display: none;">
                                    <label class="form-label">Preview</label>
                                    <div class="border rounded p-3 text-center">
                                        <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 300px;">
                                    </div>
                                </div>

                                <!-- Image Type -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                        <option value="">Select Image Type</option>
                                        <option value="main" {{ old('type') == 'main' ? 'selected' : '' }}>Main Image</option>
                                        <option value="gallery" {{ old('type') == 'gallery' ? 'selected' : '' }}>Gallery Image</option>
                                        <option value="thumbnail" {{ old('type') == 'thumbnail' ? 'selected' : '' }}>Thumbnail</option>
                                        <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                                        <option value="background" {{ old('type') == 'background' ? 'selected' : '' }}>Background</option>
                                        <option value="logo" {{ old('type') == 'logo' ? 'selected' : '' }}>Logo</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                           value="{{ old('alt_text') }}"
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
                                           value="{{ old('title') }}"
                                           placeholder="Image title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              placeholder="Additional description or notes about the image">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>
                                        Upload Image
                                    </button>
                                    <a href="{{ route('admin.images.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Upload Guidelines -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Upload Guidelines
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold">Supported Formats</h6>
                            <ul class="mb-3">
                                <li>JPEG (.jpg, .jpeg)</li>
                                <li>PNG (.png)</li>
                                <li>GIF (.gif)</li>
                                <li>WebP (.webp)</li>
                            </ul>

                            <h6 class="fw-bold">File Size</h6>
                            <p class="mb-3">Maximum file size is 10MB.</p>

                            <h6 class="fw-bold">Image Types</h6>
                            <ul class="mb-3">
                                <li><strong>Main:</strong> Primary image for entities</li>
                                <li><strong>Gallery:</strong> Additional images</li>
                                <li><strong>Thumbnail:</strong> Small preview images</li>
                                <li><strong>Banner:</strong> Header/banner images</li>
                                <li><strong>Background:</strong> Background images</li>
                                <li><strong>Logo:</strong> Logo images</li>
                            </ul>

                            <h6 class="fw-bold">Best Practices</h6>
                            <ul class="mb-0">
                                <li>Use descriptive alt text</li>
                                <li>Optimize images before upload</li>
                                <li>Use appropriate dimensions</li>
                                <li>Choose meaningful filenames</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Quick Stats
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="fw-bold text-primary fs-4">{{ \App\Models\Image::count() }}</div>
                                    <div class="text-muted small">Total Images</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-success fs-4">{{ \App\Models\Image::where('is_active', true)->count() }}</div>
                                    <div class="text-muted small">Active Images</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
        
        // Auto-fill alt text with filename if empty
        const altTextInput = document.getElementById('alt_text');
        if (!altTextInput.value) {
            const filename = input.files[0].name.replace(/\.[^/.]+$/, ""); // Remove extension
            altTextInput.value = filename.replace(/[-_]/g, ' '); // Replace hyphens and underscores with spaces
        }
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endsection
