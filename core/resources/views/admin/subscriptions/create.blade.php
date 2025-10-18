@extends('admin.layouts.master')

@section('title', 'Create Subscription')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif

            <div class="row row-cards">
                <div class="card px-0">
                    <div class="col-12">
                        <form method="POST" action="{{ route('admin.subscriptions.store') }}">
                            @csrf
                            <div class="card-header bg-dark-lt h3 text-dark bold pt-2 pb-2">
                                Create a Subscription
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- User -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">User:</label>
                                        <select name="user_id" class="form-select" required>
                                            <option value="">Select a User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Plan -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Plan:</label>
                                        <select name="plan_id" class="form-select" required>
                                            <option value="">Select a Plan</option>
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}"
                                                    {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                    {{ $plan->name }}
                                                    - ${{ number_format($plan->price, 2) }}
                                                    - {{ $plan->duration }} Days
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Method:</label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="">Select Payment Method</option>
                                            @foreach ($active_payment_methods as $method)
                                                <option value="{{ $method->id }}"
                                                    {{ old('payment_method') == $method->id ? 'selected' : '' }}>
                                                    {{ $method->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status:</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>
                                                Expired
                                            </option>
                                            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>
                                                Canceled
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex flex-row-reverse mt-4">
                                    <button type="submit" class="btn btn-dark">Create Subscription</button>
                                    <a href="{{ route('admin.subscriptions.index') }}"
                                        class="btn btn-white me-4">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
