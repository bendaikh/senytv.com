@extends($activeTemplate . '.layouts.dashboard')

@section('content')
<div class="container-fluid">

        <div class="row mb-4">
            <!-- Active Subscription Card -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">{{ __('Active Subscription') }}</h5>
                    </div>
                    <div class="card-body">
                        @if($activeSubscription)
                            <h4 class="text-primary">{{ $activeSubscription->plan->name }}</h4>
                            <p class="mb-2">
                                <strong>{{ __('Status') }}:</strong> 
                                <span class="badge bg-success">{{ ucfirst($activeSubscription->status) }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>{{ __('Expires') }}:</strong> 
                                {{ $activeSubscription->expires_at->format('M d, Y') }}
                            </p>
                            <p class="mb-0">
                                <strong>{{ __('Price') }}:</strong> 
                                ${{ number_format($activeSubscription->price, 2) }}
                            </p>
                        @else
                            <p class="text-muted">{{ __('No active subscription.') }}</p>
                            <a href="{{ route('home') }}#pricing" class="btn btn-warning btn-sm">
                                {{ __('Browse Plans') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ticket Stats Card -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">{{ __('Support Tickets') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="mb-0">{{ $ticketStats['total'] }}</h3>
                                <small class="text-muted">{{ __('Total') }}</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0 text-warning">{{ $ticketStats['open'] }}</h3>
                                <small class="text-muted">{{ __('Open') }}</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0 text-success">{{ $ticketStats['resolved'] }}</h3>
                                <small class="text-muted">{{ __('Resolved') }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('customer.tickets.create') }}" class="btn btn-info btn-sm w-100">
                                {{ __('Create New Ticket') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        @if($recentTransactions->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('Recent Transactions') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Plan') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                            <td>{{ $transaction->plan->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($transaction->amount, 2) }}</td>
                                            <td>
                                                @if($transaction->payment_status === 'Paid')
                                                    <span class="badge bg-success">{{ $transaction->payment_status }}</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">{{ $transaction->payment_status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('payment.transaction.detail', $transaction->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('customer.payments') }}" class="btn btn-warning">
                                {{ __('View All Payments') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}
.btn-warning {
    background: #ffbf23;
    border: none;
    font-weight: 600;
}
.btn-warning:hover {
    background: #e6ab1f;
}
</style>
@endsection

