@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="checkout-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">{{ __('Checkout') }}</h3>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Order Summary -->
                            <div class="col-md-5 mb-4 mb-md-0">
                                <h5 class="mb-3">{{ __('Order Summary') }}</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $plan->name }}</h6>
                                        <p class="card-text text-muted mb-2">
                                            @if(isset($plan->duration_type))
                                                {{ $plan->duration_type }}
                                            @else
                                                {{ $plan->duration }} {{ __('days') }}
                                            @endif
                                        </p>
                                        <p class="card-text text-muted small">{{ $plan->description }}</p>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('Subtotal') }}:</span>
                                            <span>${{ number_format($plan->price, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ __('Tax') }}:</span>
                                            <span>$0.00</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ __('Total') }}:</strong>
                                            <strong class="text-warning">${{ number_format($plan->price, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkout Form -->
                            <div class="col-md-7">
                                <h5 class="mb-3">{{ __('Billing Information') }}</h5>
                                <form action="{{ route('payment.process') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                   id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                   id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                               id="address" name="address" value="{{ old('address') }}" required>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="address2" class="form-label">{{ __('Address Line 2') }}</label>
                                        <input type="text" class="form-control @error('address2') is-invalid @enderror" 
                                               id="address2" name="address2" value="{{ old('address2') }}">
                                        @error('address2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" name="city" value="{{ old('city') }}" required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="state" class="form-label">{{ __('State/Province') }}</label>
                                            <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                                   id="state" name="state" value="{{ old('state') }}">
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                            <select class="form-select @error('country') is-invalid @enderror" 
                                                    id="country" name="country" required>
                                                <option value="">{{ __('Select Country') }}</option>
                                                <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                                                <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                                <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                                                <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                                <option value="ES" {{ old('country') == 'ES' ? 'selected' : '' }}>Spain</option>
                                                <option value="IT" {{ old('country') == 'IT' ? 'selected' : '' }}>Italy</option>
                                                <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                                <!-- Add more countries as needed -->
                                            </select>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="zip" class="form-label">{{ __('ZIP/Postal Code') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('zip') is-invalid @enderror" 
                                                   id="zip" name="zip" value="{{ old('zip') }}" required>
                                            @error('zip')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-warning btn-lg">
                                            {{ __('Proceed to Payment') }} - ${{ number_format($plan->price, 2) }}
                                        </button>
                                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                            {{ __('Cancel') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.checkout-section {
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
.form-label {
    font-weight: 500;
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

