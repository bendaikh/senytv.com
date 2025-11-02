@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="ticket-show-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $ticket->subject }}</h4>
                            <small class="text-muted">#{{ $ticket->id }}</small>
                        </div>
                        <div>
                            @if($ticket->status === 'open')
                                <span class="badge bg-success">{{ ucfirst($ticket->status) }}</span>
                            @elseif($ticket->status === 'in_progress')
                                <span class="badge bg-info">{{ ucfirst($ticket->status) }}</span>
                            @elseif($ticket->status === 'resolved')
                                <span class="badge bg-primary">{{ ucfirst($ticket->status) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($ticket->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>{{ __('Priority') }}:</strong>
                                    @if($ticket->priority === 'urgent')
                                        <span class="badge bg-danger">{{ ucfirst($ticket->priority) }}</span>
                                    @elseif($ticket->priority === 'high')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($ticket->priority) }}</span>
                                    @elseif($ticket->priority === 'medium')
                                        <span class="badge bg-info">{{ ucfirst($ticket->priority) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($ticket->priority) }}</span>
                                    @endif
                                </p>
                                <p class="mb-1">
                                    <strong>{{ __('Created') }}:</strong> 
                                    {{ $ticket->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>{{ __('Last Updated') }}:</strong> 
                                    {{ $ticket->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="message-content">
                            <h5 class="mb-3">{{ __('Message') }}</h5>
                            <div class="bg-light p-4 rounded">
                                {!! nl2br(e($ticket->message)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('customer.tickets') }}" class="btn btn-outline-secondary">
                        {{ __('Back to Tickets') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.ticket-show-section {
    min-height: 80vh;
    background: #f8f9fa;
}
.card {
    border: none;
    border-radius: 10px;
}
.message-content {
    line-height: 1.8;
}
</style>
@endsection

