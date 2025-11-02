@extends($activeTemplate . '.layouts.dashboard')

@section('content')
<div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        @if($subscriptions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Plan') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Started') }}</th>
                                            <th>{{ __('Expires') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subscriptions as $subscription)
                                            <tr>
                                                <td>
                                                    <strong>{{ $subscription->plan->name ?? 'N/A' }}</strong>
                                                </td>
                                                <td>
                                                    @if($subscription->status === 'active')
                                                        <span class="badge bg-success">{{ ucfirst($subscription->status) }}</span>
                                                    @elseif($subscription->status === 'expired')
                                                        <span class="badge bg-danger">{{ ucfirst($subscription->status) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>${{ number_format($subscription->price, 2) }}</td>
                                                <td>{{ $subscription->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    @if($subscription->expires_at)
                                                        {{ $subscription->expires_at->format('M d, Y') }}
                                                        @if($subscription->expires_at->isPast())
                                                            <span class="badge bg-danger ms-2">{{ __('Expired') }}</span>
                                                        @endif
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($subscription->paymentMethod)
                                                        {{ $subscription->paymentMethod->name }}
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $subscriptions->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#6c757d" class="mb-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <h4 class="text-muted">{{ __('No subscriptions found') }}</h4>
                                <p class="text-muted">{{ __('You don\'t have any active subscriptions.') }}</p>
                                <a href="{{ route('home') }}#pricing" class="btn btn-warning mt-3">
                                    {{ __('Browse Plans') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if($availablePlans->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">{{ __('Available Plans') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ __('Upgrade or purchase a new subscription plan.') }}</p>
                        <div class="row">
                            @foreach($availablePlans as $plan)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $plan->name }}</h5>
                                            <h3 class="text-primary">${{ number_format($plan->price, 2) }}</h3>
                                            <p class="text-muted">{{ $plan->description ?? '' }}</p>
                                            <a href="{{ route('payment.checkout', $plan->id) }}" class="btn btn-warning w-100">
                                                {{ __('Subscribe') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
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

