@extends($activeTemplate . '.layouts.dashboard')

@section('content')
<div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Transaction #') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Plan') }}</th>
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
                                                    <strong>${{ number_format($transaction->amount, 2) }}</strong>
                                                    <small class="text-muted">{{ $transaction->currency }}</small>
                                                </td>
                                                <td>
                                                    @if($transaction->status === 'completed')
                                                        <span class="badge bg-success">{{ ucfirst($transaction->status) }}</span>
                                                    @elseif($transaction->status === 'pending')
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
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#6c757d" class="mb-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h4 class="text-muted">{{ __('No transactions found') }}</h4>
                                <p class="text-muted">{{ __('You haven\'t made any payments yet.') }}</p>
                                <a href="{{ route('home') }}#pricing" class="btn btn-warning mt-3">
                                    {{ __('Browse Plans') }}
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

