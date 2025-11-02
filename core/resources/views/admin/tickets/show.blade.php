@extends('admin.layouts.master')
@section('title', 'Ticket #' . $ticket->id)

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Support Ticket</div>
            <h2>Ticket #{{ $ticket->id }}</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">
                Back to Tickets
            </a>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Ticket Details -->
                    <div class="card-modern mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ $ticket->subject }}</h3>
                                <small style="color: var(--text-secondary);">Created: {{ $ticket->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <div>
                                @if($ticket->status === 'open')
                                    <span class="badge bg-success">Open</span>
                                @elseif($ticket->status === 'in_progress')
                                    <span class="badge bg-info">In Progress</span>
                                @elseif($ticket->status === 'resolved')
                                    <span class="badge bg-primary">Resolved</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Priority:</strong>
                                @if($ticket->priority === 'urgent')
                                    <span class="badge bg-danger">Urgent</span>
                                @elseif($ticket->priority === 'high')
                                    <span class="badge bg-warning text-dark">High</span>
                                @elseif($ticket->priority === 'medium')
                                    <span class="badge bg-info">Medium</span>
                                @else
                                    <span class="badge bg-secondary">Low</span>
                                @endif
                            </div>
                            <hr>
                            <h5 class="mb-3">Message</h5>
                            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; white-space: pre-wrap; line-height: 1.8; word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; max-width: 100%;">
                                {{ $ticket->message }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Customer Info -->
                    <div class="card-modern mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Name:</strong><br>
                                {{ $ticket->user->full_name ?? 'N/A' }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $ticket->user->email ?? '' }}">{{ $ticket->user->email ?? 'N/A' }}</a>
                            </div>
                            <div class="mb-3">
                                <strong>Phone:</strong><br>
                                {{ $ticket->user->phone_number ?? 'N/A' }}
                            </div>
                            <div>
                                <strong>Country:</strong><br>
                                {{ $ticket->user->country ?? 'N/A' }}
                            </div>
                            @if($ticket->user)
                                <hr>
                                <a href="{{ route('admin.clients.show', $ticket->user->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    View Customer Profile
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Update Ticket -->
                    <div class="card-modern">
                        <div class="card-header">
                            <h5 class="mb-0">Update Ticket</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select">
                                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Update Ticket</button>
                            </form>

                            <hr>

                            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket->id) }}" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete Ticket</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

