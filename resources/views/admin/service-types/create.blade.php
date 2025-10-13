@extends('layouts.admin')

@section('title', 'Add Service Type')
@section('page-title', 'Add New Service Type')

@section('page-actions')
    <a href="{{ route('admin.service-types.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to List
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Service Type Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.service-types.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Service Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g. Meet & Greet, Photo Session" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Describe what this service includes..." 
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (available for booking)
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-undo me-2"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Save Service Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Service Type Ideas
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-handshake text-warning me-2"></i>
                        <small><strong>Meet & Greet:</strong> Personal meeting and photo opportunity</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-camera text-info me-2"></i>
                        <small><strong>Photo Session:</strong> Professional photography session</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-signature text-success me-2"></i>
                        <small><strong>Autograph Signing:</strong> Personalized autographs</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-building text-primary me-2"></i>
                        <small><strong>Corporate Events:</strong> Business appearances</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-heart text-danger me-2"></i>
                        <small><strong>Charity Events:</strong> Fundraising appearances</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-microphone text-warning me-2"></i>
                        <small><strong>Speaking Engagements:</strong> Public speaking</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection