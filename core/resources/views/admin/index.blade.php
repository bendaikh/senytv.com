@extends('admin.layouts.master')
@section('title', 'Admin Dashboard')
@section('content')


    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Overview</div>
            <h2>Dashboard</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#clearCacheModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                    <path stroke="none" d="M0 0h24v24H0z"></path>
                    <path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1"></path>
                    <path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1" transform="rotate(120 12 13)"></path>
                    <path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1" transform="rotate(240 12 13)"></path>
                </svg>
                Clear Cache
            </button>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if (session('success'))
                @include('admin.partials.alerts.success', ['message' => session('success')])
            @endif

            <!-- Modern Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="stats-card animate-fade-in-up">
                        <div class="stats-card-icon primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                        </div>
                        <div class="stats-card-value">{{ $total_users }}</div>
                        <div class="stats-card-label">Total Customers</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stats-card animate-fade-in-up">
                        <div class="stats-card-icon success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3v3m0 12v3"></path>
                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"></path>
                            </svg>
                        </div>
                        <div class="stats-card-value">${{ $total_earnings }}</div>
                        <div class="stats-card-label">Total Earnings</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stats-card animate-fade-in-up">
                        <div class="stats-card-icon danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12.5 17h-8.5a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v1" />
                                <path d="M9 17v1a3 3 0 0 0 3.51 2.957" />
                                <path d="M16 19h6" />
                                <path d="M19 16v6" />
                            </svg>
                        </div>
                        <div class="stats-card-value">{{ $new_subscriptions }}</div>
                        <div class="stats-card-label">New Subscriptions</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stats-card animate-fade-in-up">
                        <div class="stats-card-icon info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                <path d="M21 6.727a11.05 11.05 0 0 0 -2.794 -3.727" />
                                <path d="M3 6.727a11.05 11.05 0 0 1 2.792 -3.727" />
                            </svg>
                        </div>
                        <div class="stats-card-value">{{ $active_subscriptions }}</div>
                        <div class="stats-card-label">Active Subscriptions</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Cache Modal -->
    <div class="modal fade" id="clearCacheModal" tabindex="-1" aria-labelledby="clearCacheModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clearCacheModalLabel">Confirm Cache Clear</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to clear the dashboard cache? This will remove cached data and might
                        temporarily impact performance.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="clearCacheForm" method="POST" action="{{ route('admin.cache.clear') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, clear cache</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
