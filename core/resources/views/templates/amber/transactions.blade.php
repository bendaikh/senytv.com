@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="transactions-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">{{ __('Transaction History') }}</h3>
                    </div>
                    <div class="card-body">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Transaction #') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Plan') }}</th>
                                            <th>{{ __('Customer') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Payment Status') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>
                                                    <strong>{{ $transaction->transaction_number ?? $transaction->external_order_id }}</strong>
                                                </td>
                                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                                <td>{{ $transaction->plan->name ?? 'N/A' }}</td>
                                                <td>
                                                    <div>{{ $transaction->customer_name }}</div>
                                                    <small class="text-muted">{{ $transaction->customer_email }}</small>
                                                </td>
                                                <td>
                                                    <strong>${{ number_format($transaction->amount, 2) }}</strong>
                                                    <small class="text-muted">{{ $transaction->currency }}</small>
                                                </td>
                                                <td>
                                                    @if($transaction->status === 'completed')
                                                        <span class="badge bg-success">{{ ucfirst($transaction->status) }}</span>
                                                    @elseif($transaction->status === 'pending' || $transaction->status === 'pending_payment')
                                                        <span class="badge bg-warning text-dark">{{ ucfirst($transaction->status) }}</span>
                                                    @elseif($transaction->status === 'cancelled')
                                                        <span class="badge bg-danger">{{ ucfirst($transaction->status) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($transaction->payment_status === 'Paid')
                                                        <span class="badge bg-success">{{ $transaction->payment_status }}</span>
                                                    @elseif($transaction->payment_status === 'Pending')
                                                        <span class="badge bg-warning text-dark">{{ $transaction->payment_status }}</span>
                                                    @elseif($transaction->payment_status === 'Refunded')
                                                        <span class="badge bg-info">{{ $transaction->payment_status }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $transaction->payment_status }}</span>
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

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $transactions->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-3">
                                    <path d="M9 11H15M9 15H15M21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5Z" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h4 class="text-muted">{{ __('No transactions found') }}</h4>
                                <p class="text-muted">{{ __('You haven\'t made any transactions yet.') }}</p>
                                <a href="{{ route('home') }}" class="btn btn-warning mt-3">
                                    {{ __('Browse Plans') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.transactions-section {
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
.table th {
    background: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}
.table td {
    vertical-align: middle;
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

