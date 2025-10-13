@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-title', 'Manage Contact Messages')

@section('content')
<!-- Filter Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Messages</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Name, email, subject...">
            </div>
            <div class="col-md-4">
                <label class="form-label d-block">&nbsp;</label>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search me-2"></i>
                    Search
                </button>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh me-2"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-warning">{{ $totalMessages }}</h5>
                        <p class="card-text">Total Messages</p>
                    </div>
                    <i class="fas fa-envelope fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-danger">{{ $unreadMessages }}</h5>
                        <p class="card-text">Unread</p>
                    </div>
                    <i class="fas fa-envelope-open fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-info">{{ $readMessages }}</h5>
                        <p class="card-text">Read</p>
                    </div>
                    <i class="fas fa-eye fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-success">{{ $repliedMessages }}</h5>
                        <p class="card-text">Replied</p>
                    </div>
                    <i class="fas fa-reply fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Messages List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-envelope me-2"></i>
            Contact Messages
            @if(request()->hasAny(['status', 'search']))
                <span class="badge bg-info ms-2">Filtered</span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($messages->count() > 0)
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                            <td><strong>#{{ $message->id }}</strong></td>
                            <td>
                                <div>
                                    <strong>{{ $message->name }}</strong><br>
                                    <small class="text-muted">{{ $message->email }}</small><br>
                                    @if($message->phone)
                                        <small class="text-muted">{{ $message->phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <strong>{{ $message->subject }}</strong>
                                @if(!$message->is_read)
                                    <span class="badge bg-danger ms-1">New</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($message->message, 60) }}</td>
                            <td>
                                @if($message->is_replied)
                                    <span class="badge bg-success">Replied</span>
                                @elseif($message->is_read)
                                    <span class="badge bg-info">Read</span>
                                @else
                                    <span class="badge bg-warning">Unread</span>
                                @endif
                            </td>
                            <td>
                                {{ $message->created_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $message->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.contacts.show', $message) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$message->is_replied)
                                        <a href="{{ route('admin.contacts.reply', $message) }}" 
                                           class="btn btn-sm btn-success" 
                                           title="Reply">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.contacts.destroy', $message) }}" 
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $messages->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>No Messages Found</h5>
                @if(request()->hasAny(['status', 'search']))
                    <p>No messages match your current filters.</p>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>
                        Clear Filters
                    </a>
                @else
                    <p>No contact messages have been received yet.</p>
                @endif
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