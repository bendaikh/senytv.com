@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="payment-cancel-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm text-center">
                    <div class="card-body p-5">
                        <div class="cancel-icon mb-4">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#dc3545"/>
                                <path d="M15 9L9 15M9 9L15 15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <h2 class="text-danger mb-3">{{ __('Payment Cancelled') }}</h2>
                        <p class="text-muted mb-4">
                            {{ __('Your payment was cancelled. No charges have been made to your account.') }}
                        </p>

                        @if($transaction)
                            <div class="transaction-info bg-light p-4 rounded mb-4">
                                <p class="mb-2">
                                    <strong>{{ __('Plan') }}:</strong> {{ $transaction->plan->name }}
                                </p>
                                <p class="mb-0">
                                    <strong>{{ __('Amount') }}:</strong> ${{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                </p>
                            </div>

                            <p class="text-muted mb-4">
                                {{ __('If you experienced any issues during checkout, please contact our support team.') }}
                            </p>

                            <div class="action-buttons">
                                <a href="{{ route('payment.checkout', $transaction->plan_id) }}" class="btn btn-warning me-2">
                                    {{ __('Try Again') }}
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    {{ __('Return to Home') }}
                                </a>
                            </div>
                        @else
                            <div class="action-buttons">
                                <a href="{{ route('home') }}" class="btn btn-warning">
                                    {{ __('Return to Home') }}
                                </a>
                            </div>
                        @endif

                        <div class="help-section mt-5 pt-4 border-top">
                            <h5 class="mb-3">{{ __('Need Help?') }}</h5>
                            <p class="text-muted">
                                {{ __('If you\'re having trouble completing your purchase, our support team is here to help.') }}
                            </p>
                            @if(!empty(whatsapp()))
                                <a href="https://wa.me/{{ whatsapp() }}?text={{ urlencode(__('I need help with my payment')) }}" 
                                   class="btn btn-success" target="_blank">
                                    <svg width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                    </svg>
                                    {{ __('Contact Support on WhatsApp') }}
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
.payment-cancel-section {
    min-height: 80vh;
    background: #f8f9fa;
}
.card {
    border: none;
    border-radius: 10px;
}
.cancel-icon svg {
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}
.btn-warning {
    background: #ffbf23;
    border: none;
    font-weight: 600;
}
.btn-warning:hover {
    background: #e6ab1f;
}
.btn-success {
    display: inline-flex;
    align-items: center;
}
</style>
@endsection

