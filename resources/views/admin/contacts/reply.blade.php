@extends('layouts.admin')

@section('title', 'Reply to Message')
@section('page-title', 'Reply to Message #' . $message->id)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        <a href="{{ route('admin.contacts.show', $message) }}" class="btn btn-info">
            <i class="fas fa-eye me-2"></i>
            View Message
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Original Message -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Original Message
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>From:</strong> {{ $message->name }} ({{ $message->email }})
                </div>
                <div class="mb-3">
                    <strong>Subject:</strong> {{ $message->subject }}
                </div>
                <div class="mb-3">
                    <strong>Received:</strong> {{ $message->created_at->format('M d, Y \a\t h:i A') }}
                </div>
                <div class="mb-3">
                    <strong>Message:</strong>
                    <div class="mt-2 p-3 bg-dark rounded border">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reply Form -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-reply me-2"></i>
                    Send Reply
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contacts.reply.send', $message) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="to" class="form-label">To:</label>
                        <input type="text" 
                               class="form-control" 
                               id="to" 
                               value="{{ $message->name }} <{{ $message->email }}>" 
                               readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject:</label>
                        <input type="text" 
                               class="form-control" 
                               id="subject" 
                               value="Re: {{ $message->subject }}" 
                               readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reply_message" class="form-label">Your Reply *</label>
                        <textarea class="form-control @error('reply_message') is-invalid @enderror" 
                                  id="reply_message" 
                                  name="reply_message" 
                                  rows="8" 
                                  placeholder="Type your reply here..." 
                                  required>{{ old('reply_message') }}</textarea>
                        @error('reply_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Your reply will be sent via email to the customer.</div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.contacts.show', $message) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Email Preview -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Email Preview
                </h6>
            </div>
            <div class="card-body">
                <div class="small text-muted">
                    <p><strong>From:</strong> {{ config('mail.from.name') }} &lt;{{ config('mail.from.address') }}&gt;</p>
                    <p><strong>To:</strong> {{ $message->name }} &lt;{{ $message->email }}&gt;</p>
                    <p><strong>Subject:</strong> Re: {{ $message->subject }}</p>
                    <hr>
                    <p><strong>Message will include:</strong></p>
                    <ul>
                        <li>Your custom reply</li>
                        <li>Reference to original message</li>
                        <li>Contact information</li>
                        <li>Professional signature</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-resize textarea
    document.getElementById('reply_message').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endpush