@extends($activeTemplate . '.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0">{{ __('Support Tickets') }}</h3>
                <p class="text-muted mb-0">{{ __('Manage your support tickets and requests.') }}</p>
            </div>
            <a href="{{ route('customer.tickets.create') }}" class="btn btn-warning">
                {{ __('Create New Ticket') }}
            </a>
        </div>
    </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if($tickets->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Ticket #') }}</th>
                                            <th>{{ __('Subject') }}</th>
                                            <th>{{ __('Priority') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Created') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <td>#{{ $ticket->id }}</td>
                                                <td>
                                                    <strong>{{ $ticket->subject }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($ticket->message, 50) }}</small>
                                                </td>
                                                <td>
                                                    @if($ticket->priority === 'urgent')
                                                        <span class="badge bg-danger">{{ ucfirst($ticket->priority) }}</span>
                                                    @elseif($ticket->priority === 'high')
                                                        <span class="badge bg-warning text-dark">{{ ucfirst($ticket->priority) }}</span>
                                                    @elseif($ticket->priority === 'medium')
                                                        <span class="badge bg-info">{{ ucfirst($ticket->priority) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($ticket->priority) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ticket->status === 'open')
                                                        <span class="badge bg-success">{{ ucfirst($ticket->status) }}</span>
                                                    @elseif($ticket->status === 'in_progress')
                                                        <span class="badge bg-info">{{ ucfirst($ticket->status) }}</span>
                                                    @elseif($ticket->status === 'resolved')
                                                        <span class="badge bg-primary">{{ ucfirst($ticket->status) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($ticket->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('customer.tickets.show', $ticket->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        {{ __('View') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $tickets->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#6c757d" class="mb-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h4 class="text-muted">{{ __('No tickets found') }}</h4>
                                <p class="text-muted">{{ __('You haven\'t created any support tickets yet.') }}</p>
                                <a href="{{ route('customer.tickets.create') }}" class="btn btn-warning mt-3">
                                    {{ __('Create Your First Ticket') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}
.btn-warning {
    background: #ffbf23;
    border: none;
    font-weight: 600;
}
</style>
@endsection

