@extends($activeTemplate . '.layouts.app')

@section('content')
<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark text-center">
                        <h3 class="mb-0">{{ __('Customer Login') }}</h3>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('customer.login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 mb-3">
                                {{ __('Login') }}
                            </button>

                            <div class="text-center">
                                <p class="mb-0">{{ __('Don\'t have an account?') }} 
                                    <a href="{{ route('customer.register') }}">{{ __('Register here') }}</a>
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

