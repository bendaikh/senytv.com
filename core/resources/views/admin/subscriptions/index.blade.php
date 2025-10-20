@extends('admin.layouts.master')

@section('title', 'Subscriptions History')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Subscription Management</div>
            <h2>Subscriptions History</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                New Subscription
            </a>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card-modern">
                <!-- Table Controls -->
                <div class="card-body" style="border-bottom: 1px solid var(--border-color); padding: 1rem 1.5rem;">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">Show</span>
                                <form method="GET" style="display: inline;">
                                    <input type="number" name="perPage" class="form-control form-control-sm" style="width: 80px; display: inline-block;"
                                        value="{{ $subscriptions->perPage() }}" aria-label="subscription count">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                </form>
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">entries</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="d-flex align-items-center gap-2 justify-content-md-end">
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">Search:</span>
                                <input type="text" name="search" class="form-control form-control-sm" style="max-width: 250px;"
                                    placeholder="Search..." value="{{ request('search') }}">
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modern Table -->
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Plan</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Transaction ID</th>
                                <th>Created At</th>
                                <th>Expires At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.clients.show', $subscription->user->id) }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                            {{ $subscription->user->full_name }}
                                        </a>
                                    </td>
                                    <td style="font-weight: 500;">{{ $subscription->plan->name }}</td>
                                    <td style="font-weight: 600;">{{ getCurrency($subscription->user->country) . $subscription->price }}</td>
                                    <td>
                                        @if ($subscription->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif ($subscription->status == 'expired')
                                            <span class="badge badge-danger">Expired</span>
                                        @elseif ($subscription->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge" style="background: #6c757d; color: #fff;">Canceled</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscription->paymentMethod->name ?? 'N/A' }}</td>
                                    <td style="font-family: monospace; font-size: 0.875rem;">{{ $subscription->transaction_id }}</td>
                                    <td style="color: var(--text-secondary); font-size: 0.875rem;">{{ $subscription->created_at->format('Y-m-d H:i') }}</td>
                                    <td style="color: var(--text-secondary); font-size: 0.875rem;">{{ $subscription->expires_at ? $subscription->expires_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color);">
                    @include('admin.partials.pagination', ['paginator' => $subscriptions])
                </div>
            </div>
        </div>
    </div>
@endsection
