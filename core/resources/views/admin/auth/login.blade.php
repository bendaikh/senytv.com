@extends('admin.layouts.auth')
@section('title', 'Admin Login')
@section('content')
    <div class="card card-md">
        <div class="card-body">

            <h2 class="h2 text-center mb-4">Admin Panel Login</h2>

            @if (session('success'))
                @include('admin.partials.alerts.success', ['message' => session('success')])
            @endif
            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif

            <form method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" placeholder="your@email.com" name="email" />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Password
                        <span class="form-label-description">
                            <a href="{{ route('admin.password.request') }}">I forgot password</a>
                        </span>
                    </label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" placeholder="Your password" name="password" />
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" />
                        <span class="form-check-label">Remember me on this device</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
