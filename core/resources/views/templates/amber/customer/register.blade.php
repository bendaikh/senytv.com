@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center pt-5 pb-5">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark text-center">
                        <h3 class="mb-0">{{ __('Customer Registration') }}</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('customer.register.submit') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="full_name" class="form-label">{{ __('Full Name') }}</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">{{ __('Phone Number') }}</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">{{ __('Country') }}</label>
                                    <select class="form-select @error('country') is-invalid @enderror" 
                                            id="country" name="country" required>
                                        <option value="">{{ __('Select Country') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country['code'] }}" 
                                                    {{ old('country') === $country['code'] ? 'selected' : '' }}>
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 mb-3">
                                {{ __('Register') }}
                            </button>

                            <div class="text-center">
                                <p class="mb-0">{{ __('Already have an account?') }} 
                                    <a href="{{ route('customer.login') }}">{{ __('Login here') }}</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.auth-section {
    min-height: 80vh;
    background: #f8f9fa;
    padding-top: 5rem !important;
    padding-bottom: 4rem !important;
}
.card {
    border: none;
    border-radius: 10px;
    margin-top: 2rem;
}
.btn-warning {
    background: #ffbf23;
    border: none;
    font-weight: 600;
}
.btn-warning:hover {
    background: #e6ab1f;
}
@media (max-width: 768px) {
    .auth-section {
        padding-top: 3rem !important;
        padding-bottom: 2rem !important;
    }
}
</style>
@endsection

