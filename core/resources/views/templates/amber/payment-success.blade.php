@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="payment-result-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm text-center">
                    <div class="card-body p-5">
                        @if($transaction && $transaction->payment_status === 'Paid')
                            <!-- Success State -->
                            <div class="success-icon mb-4">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" fill="#28a745"/>
                                    <path d="M8 12L11 15L16 9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h2 class="text-success mb-3">{{ __('Payment Successful!') }}</h2>
                            <p class="text-muted mb-4">{{ __('Thank you for your purchase. Your payment has been processed successfully.') }}</p>

                            <div class="transaction-details bg-light p-4 rounded mb-4">
                                <h5 class="mb-3">{{ __('Transaction Details') }}</h5>
                                <div class="row text-start">
                                    <div class="col-6">
                                        <p><strong>{{ __('Transaction Number') }}:</strong></p>
                                        <p><strong>{{ __('Plan') }}:</strong></p>
                                        <p><strong>{{ __('Amount Paid') }}:</strong></p>
                                        <p><strong>{{ __('Payment Status') }}:</strong></p>
                                        <p><strong>{{ __('Date') }}:</strong></p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $transaction->transaction_number }}</p>
                                        <p>{{ $transaction->plan->name }}</p>
                                        <p>${{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</p>
                                        <p><span class="badge bg-success">{{ $transaction->payment_status }}</span></p>
                                        <p>{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($transaction->subscription)
                                <div class="alert alert-info">
                                    <strong>{{ __('Subscription Active!') }}</strong><br>
                                    {{ __('Your subscription is now active and will expire on') }} 
                                    <strong>{{ $transaction->subscription->expires_at->format('M d, Y') }}</strong>
                                </div>
                            @endif

                            <p class="text-muted small mb-4">
                                {{ __('A confirmation email has been sent to') }} <strong>{{ $transaction->customer_email }}</strong>
                            </p>
                        @elseif($transaction)
                            <!-- Pending State -->
                            <div class="pending-icon mb-4">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" fill="#ffc107"/>
                                    <path d="M12 7V12L15 15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <h2 class="text-warning mb-3">{{ __('Payment Pending') }}</h2>
                            <p class="text-muted mb-4">{{ __('Your payment is being processed. This may take a few moments.') }}</p>

                            <div class="transaction-details bg-light p-4 rounded mb-4">
                                <div class="row text-start">
                                    <div class="col-6">
                                        <p><strong>{{ __('Transaction Number') }}:</strong></p>
                                        <p><strong>{{ __('Status') }}:</strong></p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $transaction->transaction_number }}</p>
                                        <p><span class="badge bg-warning">{{ $transaction->payment_status }}</span></p>
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted small mb-4">
                                {{ __('You will receive a confirmation email once the payment is confirmed.') }}
                            </p>
                        @else
                            <!-- No Transaction Found -->
                            <div class="info-icon mb-4">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" fill="#17a2b8"/>
                                    <path d="M12 16V12M12 8H12.01" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <h2 class="text-info mb-3">{{ __('Transaction Not Found') }}</h2>
                            <p class="text-muted mb-4">{{ __('We couldn\'t find any transaction details at this time.') }}</p>
                        @endif

                        <div class="action-buttons mt-4">
                            <a href="{{ route('home') }}" class="btn btn-warning me-2">
                                {{ __('Return to Home') }}
                            </a>
                            @if($transaction)
                                <a href="{{ route('payment.transaction.detail', $transaction->id) }}" class="btn btn-outline-secondary">
                                    {{ __('View Details') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.payment-result-section {
    min-height: 80vh;
    background: #f8f9fa;
}
.card {
    border: none;
    border-radius: 10px;
}
.success-icon svg, .pending-icon svg, .info-icon svg {
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}
.transaction-details {
    text-align: left;
}
.transaction-details p {
    margin-bottom: 0.5rem;
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

