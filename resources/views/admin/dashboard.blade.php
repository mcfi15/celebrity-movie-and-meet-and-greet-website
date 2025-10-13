@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-warning">{{ $totalBookings }}</h5>
                        <p class="card-text">Total Bookings</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-success">{{ $pendingBookings }}</h5>
                        <p class="card-text">Pending Bookings</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-info">{{ $totalCelebrities }}</h5>
                        <p class="card-text">Total Celebrities</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-danger">{{ $unreadMessages }}</h5>
                        <p class="card-text">Unread Messages</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Bookings -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Recent Bookings
                </h5>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Celebrity</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->customer_name }}</td>
                                    <td>{{ $booking->celebrity->name }}</td>
                                    <td>{{ $booking->serviceType->name }}</td>
                                    <td>
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($booking->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">
                            View All Bookings
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>No bookings yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.celebrities.create') }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-2"></i>
                        Add New Celebrity
                    </a>
                    <a href="{{ route('admin.service-types.create') }}" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>
                        Add Service Type
                    </a>
                    <a href="{{ route('admin.bookings.index') }}?status=pending" class="btn btn-warning">
                        <i class="fas fa-clock me-2"></i>
                        Review Pending Bookings
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-cog me-2"></i>
                        Site Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Recent Messages
                </h5>
            </div>
            <div class="card-body">
                @if($recentMessages->count() > 0)
                    @foreach($recentMessages as $message)
                        <div class="border-bottom border-secondary pb-2 mb-2">
                            <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            <p class="mb-1"><strong>{{ $message->name }}</strong></p>
                            <p class="small text-muted mb-0">{{ Str::limit($message->message, 50) }}</p>
                        </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-primary">
                            View All Messages
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="small">No messages yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Booking Trends (Last 30 Days)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="bookingChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Booking Chart
    const ctx = document.getElementById('bookingChart').getContext('2d');
    const bookingChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Bookings',
                data: @json($chartData),
                borderColor: '#ffd700',
                backgroundColor: 'rgba(255, 215, 0, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
</script>
@endpush