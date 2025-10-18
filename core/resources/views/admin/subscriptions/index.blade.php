@extends('admin.layouts.master')

@section('title', 'Subscriptions History')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Subscriptions</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <form method="GET">
                                        <input type="number" name="perPage" class="form-control form-control-sm"
                                            id="paginationInput" value="{{ $subscriptions->perPage() }}"
                                            aria-label="subscription count">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    </form>
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-secondary">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <form method="GET">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            aria-label="Search subscriptions" placeholder="Search..."
                                            value="{{ request('search') }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
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
                                            <a href="{{ route('admin.clients.show', $subscription->user->id) }}">
                                                {{ $subscription->user->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ $subscription->plan->name }}</td>
                                        <td>{{ getCurrency($subscription->user->country) . $subscription->price }}</td>
                                        <td>
                                            @if ($subscription->status == 'active')
                                                <span class="badge bg-success text-success-fg">Active</span>
                                            @elseif ($subscription->status == 'expired')
                                                <span class="badge bg-danger text-danger-fg">Expired</span>
                                            @elseif ($subscription->status == 'pending')
                                                <span class="badge bg-warning text-warning-fg">Pending</span>
                                            @else
                                                <span class="badge bg-secondary text-secondary-fg">Canceled</span>
                                            @endif
                                        </td>
                                        <td>{{ $subscription->paymentMethod->name ?? 'N/A' }}</td>
                                        <td>{{ $subscription->transaction_id }}</td>
                                        <td>{{ $subscription->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $subscription->expires_at ? $subscription->expires_at->format('Y-m-d') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @include('admin.partials.pagination', ['paginator' => $subscriptions])

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
