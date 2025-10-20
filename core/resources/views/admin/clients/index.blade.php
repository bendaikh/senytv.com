@extends('admin.layouts.master')
@section('title', 'Members Directory')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Client Management</div>
            <h2>Active Members Directory</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                Add New Client
            </a>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if ($clients->total() > 0)
                <div class="row g-4 mb-4">
                    @foreach ($clients as $client)
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('admin.clients.show', ['client' => $client->id]) }}" style="text-decoration: none; color: inherit;">
                                <div class="card-modern">
                                    <div class="card-body text-center">
                                        <div class="avatar avatar-xl mb-3 rounded-circle" style="background-image: url({{ asset('assets/dist/images/flags/' . $client->country . '.svg') }}); width: 80px; height: 80px; margin: 0 auto; background-size: cover; background-position: center;"></div>
                                        <h3 class="m-0 mb-2" style="font-size: 1.125rem; font-weight: 600;">{{ $client->full_name }}</h3>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">{{ $client->email }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                @include('admin.partials.pagination', ['paginator' => $clients])
            @else
                <div class="card-modern text-center" style="padding: 3rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem; color: var(--text-secondary);">
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                    </svg>
                    <h3 style="margin-bottom: 0.5rem;">No Clients Found</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Get started by adding your first client.</p>
                    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">Add First Client</a>
                </div>
            @endif
        </div>
    </div>
@endsection
