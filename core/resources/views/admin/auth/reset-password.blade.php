@extends('admin.layouts.auth')
@section('title', 'Admin Reset Password')
@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Reset Password</h2>

            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif

            <form method="post" action="{{ route('admin.password.reset.submit') }}">
                @csrf
                <input type="hidden" name="token" value="{{ request()->token }}">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" class="form-control" placeholder="your@email.com" name="email"
                        value="{{ request()->email }}" required />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" class="form-control" name="password" required />
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                        required />
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Reset Password
                    </button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('admin.login') }}">Back to Login</a>
            </div>
        </div>
    </div>
@endsection
