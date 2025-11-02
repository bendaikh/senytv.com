@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="dashboard-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-0">{{ __('Welcome,') }} {{ $user->full_name }}</h2>
                <p class="text-muted">{{ __('Manage your account, subscriptions, and support tickets.') }}</p>
            </div>
        </div>

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

        <!-- Quick Links -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <a href="{{ route('customer.payments') }}" class="card shadow-sm text-decoration-none text-dark h-100">
                    <div class="card-body text-center">
                        <svg width="48" height="48" class="mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h5>{{ __('Payments') }}</h5>
                        <p class="text-muted mb-0">{{ __('View payment history') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('customer.plans') }}" class="card shadow-sm text-decoration-none text-dark h-100">
                    <div class="card-body text-center">
                        <svg width="48" height="48" class="mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <h5>{{ __('Plans') }}</h5>
                        <p class="text-muted mb-0">{{ __('Manage subscriptions') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('customer.tickets') }}" class="card shadow-sm text-decoration-none text-dark h-100">
                    <div class="card-body text-center">
                        <svg width="48" height="48" class="mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h5>{{ __('Tickets') }}</h5>
                        <p class="text-muted mb-0">{{ __('Support requests') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('home') }}" class="card shadow-sm text-decoration-none text-dark h-100">
                    <div class="card-body text-center">
                        <svg width="48" height="48" class="mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <h5>{{ __('Home') }}</h5>
                        <p class="text-muted mb-0">{{ __('Back to home') }}</p>
                    </div>
                </a>
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
</section>

<style>
.dashboard-section {
    min-height: 80vh;
    background: #f8f9fa;
}
.card {
    border: none;
    border-radius: 10px;
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

