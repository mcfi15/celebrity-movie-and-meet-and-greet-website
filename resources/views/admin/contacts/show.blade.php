@extends('layouts.admin')

@section('title', 'Message Details')
@section('page-title', 'Message #' . $message->id)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to List
        </a>
        @if(!$message->replied_at)
            <a href="{{ route('admin.contacts.reply', $message) }}" class="btn btn-success">
                <i class="fas fa-reply me-2"></i>
                Reply
            </a>
        @endif
        <form action="{{ route('admin.contacts.destroy', $message) }}" 
              method="POST" 
              class="d-inline" 
              onsubmit="return confirmDelete()">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>
                Delete
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Message Status -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1">
                            @if($message->replied_at)
                                <span class="badge bg-success fs-4">Replied</span>
                            @elseif($message->is_read)
                                <span class="badge bg-info fs-4">Read</span>
                            @else
                                <span class="badge bg-warning fs-4">Unread</span>
                            @endif
                        </h2>
                        <p class="text-muted">Message Status</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-info">{{ $message->created_at->diffForHumans() }}</h2>
                        <p class="text-muted">Received</p>
                    </div>
                    <div class="col-md-3 text-center">
                        @if($message->replied_at)
                            <h2 class="mb-1 text-success">{{ $message->replied_at->diffForHumans() }}</h2>
                            <p class="text-muted">Replied</p>
                        @else
                            <h2 class="mb-1 text-warning">Not Replied</h2>
                            <p class="text-muted">Awaiting Response</p>
                        @endif
                    </div>
                    <div class="col-md-3 text-center">
                        <h2 class="mb-1 text-primary">{{ $message->created_at->format('M d, Y') }}</h2>
                        <p class="text-muted">Date Received</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sender Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Sender Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Name:</strong></div>
                    <div class="col-sm-8">{{ $message->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Email:</strong></div>
                    <div class="col-sm-8">
                        <a href="mailto:{{ $message->email }}" class="text-warning">
                            {{ $message->email }}
                        </a>
                    </div>
                </div>
                @if($message->phone)
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Phone:</strong></div>
                        <div class="col-sm-8">
                            <a href="tel:{{ $message->phone }}" class="text-warning">
                                {{ $message->phone }}
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Submitted:</strong></div>
                    <div class="col-sm-8">{{ $message->created_at->format('l, F d, Y \a\t h:i A') }}</div>
                </div>
            </div>
        </div>
        
        @if(!$message->replied_at)
            <!-- Quick Reply -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-reply me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.contacts.reply', $message) }}" class="btn btn-success">
                            <i class="fas fa-reply me-2"></i>
                            Reply to Message
                        </a>
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-info">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Open in Email Client
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Message Content -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Message Content
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Subject:</strong>
                    <h6 class="mt-1">{{ $message->subject }}</h6>
                </div>
                
                <div class="mb-3">
                    <strong>Message:</strong>
                    <div class="mt-2 p-3 bg-dark rounded border">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        @if($message->replied_at)
            <!-- Reply Status -->
            <div class="card mt-4 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Reply Sent
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Replied on:</strong> {{ $message->replied_at->format('l, F d, Y \a\t h:i A') }}
                    </p>
                    <p class="mb-0">
                        <small class="text-muted">{{ $message->replied_at->diffForHumans() }}</small>
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this message? This action cannot be undone.');
    }
</script>
@endpush