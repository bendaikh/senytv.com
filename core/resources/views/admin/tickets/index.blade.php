@extends('admin.layouts.master')
@section('title', 'Support Tickets')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Customer Support</div>
            <h2>Support Tickets</h2>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card-modern">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-fill">
                                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Total Tickets</div>
                                    <div style="font-size: 1.5rem; font-weight: 600;">{{ $stats['total'] }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-color);">
                                    <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-modern">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-fill">
                                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Open</div>
                                    <div style="font-size: 1.5rem; font-weight: 600; color: #28a745;">{{ $stats['open'] }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-modern">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-fill">
                                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">In Progress</div>
                                    <div style="font-size: 1.5rem; font-weight: 600; color: #17a2b8;">{{ $stats['in_progress'] }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-modern">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-fill">
                                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Resolved</div>
                                    <div style="font-size: 1.5rem; font-weight: 600; color: #007bff;">{{ $stats['resolved'] }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-modern mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by subject, message, or customer..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                                <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tickets Table -->
            @if ($tickets->total() > 0)
                <div class="card-modern">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Customer</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td><strong>#{{ $ticket->id }}</strong></td>
                                        <td>
                                            <div>{{ $ticket->user->full_name ?? 'N/A' }}</div>
                                            <small style="color: var(--text-secondary);">{{ $ticket->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ \Illuminate\Support\Str::limit($ticket->subject, 50) }}</strong>
                                            <br>
                                            <small style="color: var(--text-secondary);">{{ \Illuminate\Support\Str::limit($ticket->message, 60) }}</small>
                                        </td>
                                        <td>
                                            @if($ticket->priority === 'urgent')
                                                <span class="badge bg-danger">Urgent</span>
                                            @elseif($ticket->priority === 'high')
                                                <span class="badge bg-warning text-dark">High</span>
                                            @elseif($ticket->priority === 'medium')
                                                <span class="badge bg-info">Medium</span>
                                            @else
                                                <span class="badge bg-secondary">Low</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->status === 'open')
                                                <span class="badge bg-success">Open</span>
                                            @elseif($ticket->status === 'in_progress')
                                                <span class="badge bg-info">In Progress</span>
                                            @elseif($ticket->status === 'resolved')
                                                <span class="badge bg-primary">Resolved</span>
                                            @else
                                                <span class="badge bg-secondary">Closed</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @include('admin.partials.pagination', ['paginator' => $tickets])
            @else
                <div class="card-modern text-center" style="padding: 3rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem; color: var(--text-secondary);">
                        <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 style="margin-bottom: 0.5rem;">No Tickets Found</h3>
                    <p style="color: var(--text-secondary);">No support tickets have been created yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

