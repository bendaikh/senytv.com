@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="transaction-detail-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ __('Transaction Details') }}</h3>
                        <a href="{{ route('payment.transactions') }}" class="btn btn-sm btn-dark">
                            {{ __('Back to Transactions') }}
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Left Column: Transaction Information -->
                            <div class="col-md-7">
                                <h5 class="mb-3">{{ __('Transaction Information') }}</h5>
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">{{ __('Transaction Number') }}:</td>
                                            <td><strong>{{ $transaction->transaction_number ?? 'N/A' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">{{ __('External Order ID') }}:</td>
                                            <td><code>{{ $transaction->external_order_id }}</code></td>
                                        </tr>
                                        @if($transaction->request_id)
                                            <tr>
                                                <td class="text-muted">{{ __('Request ID') }}:</td>
                                                <td><code>{{ $transaction->request_id }}</code></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted">{{ __('Date Created') }}:</td>
                                            <td>{{ $transaction->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                        @if($transaction->completed_at)
                                            <tr>
                                                <td class="text-muted">{{ __('Date Completed') }}:</td>
                                                <td>{{ $transaction->completed_at->format('F d, Y h:i A') }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted">{{ __('Amount') }}:</td>
                                            <td>
                                                <h4 class="mb-0">${{ number_format($transaction->amount, 2) }} <small class="text-muted">{{ $transaction->currency }}</small></h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">{{ __('Status') }}:</td>
                                            <td>
                                                @if($transaction->status === 'completed')
                                                    <span class="badge bg-success fs-6">{{ ucfirst($transaction->status) }}</span>
                                                @elseif($transaction->status === 'pending' || $transaction->status === 'pending_payment')
                                                    <span class="badge bg-warning text-dark fs-6">{{ ucfirst($transaction->status) }}</span>
                                                @elseif($transaction->status === 'cancelled')
                                                    <span class="badge bg-danger fs-6">{{ ucfirst($transaction->status) }}</span>
                                                @else
                                                    <span class="badge bg-secondary fs-6">{{ ucfirst($transaction->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">{{ __('Payment Status') }}:</td>
                                            <td>
                                                @if($transaction->payment_status === 'Paid')
                                                    <span class="badge bg-success fs-6">{{ $transaction->payment_status }}</span>
                                                @elseif($transaction->payment_status === 'Pending')
                                                    <span class="badge bg-warning text-dark fs-6">{{ $transaction->payment_status }}</span>
                                                @elseif($transaction->payment_status === 'Refunded')
                                                    <span class="badge bg-info fs-6">{{ $transaction->payment_status }}</span>
                                                @else
                                                    <span class="badge bg-secondary fs-6">{{ $transaction->payment_status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr class="my-4">

                                <h5 class="mb-3">{{ __('Customer Information') }}</h5>
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted" style="width: 40%;">{{ __('Name') }}:</td>
                                            <td>{{ $transaction->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">{{ __('Email') }}:</td>
                                            <td>{{ $transaction->customer_email }}</td>
                                        </tr>
                                        @if($transaction->customer_phone)
                                            <tr>
                                                <td class="text-muted">{{ __('Phone') }}:</td>
                                                <td>{{ $transaction->customer_phone }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                @if($transaction->billing_address)
                                    <hr class="my-4">
                                    <h5 class="mb-3">{{ __('Billing Address') }}</h5>
                                    <address>
                                        {{ $transaction->billing_address['address1'] ?? '' }}<br>
                                        @if(!empty($transaction->billing_address['address2']))
                                            {{ $transaction->billing_address['address2'] }}<br>
                                        @endif
                                        {{ $transaction->billing_address['city'] ?? '' }}
                                        @if(!empty($transaction->billing_address['state']))
                                            , {{ $transaction->billing_address['state'] }}
                                        @endif
                                        {{ $transaction->billing_address['zip'] ?? '' }}<br>
                                        {{ $transaction->billing_address['country'] ?? '' }}
                                    </address>
                                @endif
                            </div>

                            <!-- Right Column: Plan & Subscription Info -->
                            <div class="col-md-5">
                                @if($transaction->plan)
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('Plan Details') }}</h5>
                                            <h6 class="text-primary">{{ $transaction->plan->name }}</h6>
                                            <p class="card-text small">{{ $transaction->plan->description }}</p>
                                            <p class="mb-0">
                                                <strong>{{ __('Duration') }}:</strong> {{ $transaction->plan->duration }} {{ __('days') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if($transaction->subscription)
                                    <div class="card bg-success text-white mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('Subscription Status') }}</h5>
                                            <p class="mb-2">
                                                <strong>{{ __('Status') }}:</strong> 
                                                <span class="badge bg-light text-success">{{ ucfirst($transaction->subscription->status) }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <strong>{{ __('Expires On') }}:</strong><br>
                                                {{ $transaction->subscription->expires_at->format('F d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if($transaction->products)
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ __('Order Items') }}</h5>
                                            @foreach($transaction->products as $product)
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div>
                                                        <strong>{{ $product['name'] }}</strong><br>
                                                        <small class="text-muted">{{ __('Qty') }}: {{ $product['quantity'] }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        ${{ number_format($product['price'], 2) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ __('Total') }}:</strong>
                                                <strong>${{ number_format($transaction->amount, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                @if($transaction->payment_status !== 'Paid' && $transaction->payment_url)
                                    <a href="{{ $transaction->payment_url }}" class="btn btn-warning me-2" target="_blank">
                                        {{ __('Complete Payment') }}
                                    </a>
                                @endif
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    {{ __('Back to Home') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.transaction-detail-section {
    min-height: 80vh;
    background: #f8f9fa;
}
.card {
    border: none;
    border-radius: 10px;
}
.card-header {
    border-radius: 10px 10px 0 0 !important;
}
.table-borderless td {
    padding: 0.5rem 0;
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

