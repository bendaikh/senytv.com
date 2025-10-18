@extends('admin.layouts.master')
@section('title', 'Members Info')


@section('content')


    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    @if (session('success'))
                        @include('admin.partials.alerts.success', ['message' => session('success')])
                    @endif
                    @if ($errors->any())
                        @include('admin.partials.alerts.error', [
                            'title' => 'There were some errors with your submission.',
                        ])
                    @endif
                    <div class="card">
                        <div class="px-3 py-3 bg-blue-lt relative">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-xl mb-3 rounded"
                                        style="background-image: url({{ asset('assets/dist/images/flags/' . strtolower($client->country) . '.svg') }});border-radius:50%!important">
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h4 mb-0">
                                        ID: {{ $client->full_name }}
                                    </div>
                                    <div class="mb-2 small">Member since: {{ $client->created_at }}</div>
                                </div>
                            </div>
                        </div>
                        <form class="card-body" method="post" action="{{ route('admin.clients.update', $client->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <span class="form-label mb-1">Full Name:</span>
                                <input type="text" class="form-control" name="name" value="{{ $client->full_name }}">
                            </div>
                            <div class="mb-2">
                                <span class="form-label mb-1">Email address:</span>
                                <input type="text" class="form-control" name="email" value="{{ $client->email }}">
                            </div>

                            <div class="mb-2">
                                <span class="form-label mb-1">Phone Number:</span>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ $client->phone_number }}">
                            </div>

                            <div class="input-group mb-2">
                                <span class="input-group-text">Register IP:</span>
                                <input type="text" class="form-control" value="{{ $client->ip ?? '0.0.0.0' }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-block btn-primary w-100">Update user data</button>
                        </form>

                    </div>
                </div>
                <div class="col-md-6 col-xl-8">

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title me-3 text-nowrap">Subscription History</h3>
                        </div>
                        <div class=" table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Plan name</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>language</th>
                                        <th>Duration</th>
                                        <th>Start at</th>
                                        <th>Expire at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($client->subscriptions as $index => $subscription)
                                        <tr>
                                            <td>{{ $subscription->plan->name ?? 'N/A' }}</td>
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
                                            <td class="text-secondary">
                                                {{ getCurrency($client->country) . $subscription->price }}
                                            </td>

                                            <td class="text-secondary">
                                                {{ getLanguageName($subscription->plan->language) }}
                                            </td>

                                            <td class="text-secondary">
                                                {{ $subscription->plan->duration }} Days
                                            </td>

                                            <td class="text-secondary">
                                                {{ $subscription->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="text-secondary">
                                                {{ $subscription->expires_at->format('Y-m-d') }}
                                            </td>
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
