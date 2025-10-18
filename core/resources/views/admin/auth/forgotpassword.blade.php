@extends('admin.layouts.auth')
@section('title', 'Admin Forgot Password')
@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Forgot Password</h2>

            @if (session('success'))
                @include('admin.partials.alerts.success', ['message' => session('success')])
            @endif
            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif

            <form method="post" action="{{ route('admin.password.email.send') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" class="form-control" placeholder="your@email.com" name="email"
                        required />
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Send Password Reset Link
                    </button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('admin.login') }}">Back to Login</a>
            </div>
        </div>
    </div>
@endsection
