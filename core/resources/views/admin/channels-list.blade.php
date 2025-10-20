@extends('admin.layouts.master')

@section('title', 'Channels List')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Channel Management</div>
            <h2>All Channels</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChannelModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                Add Channel
            </button>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card-modern">
                <!-- Table Controls -->
                <div class="card-body" style="border-bottom: 1px solid var(--border-color); padding: 1rem 1.5rem;">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex align-items-center gap-2">
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">Show</span>
                                <input type="number" name="perPage" class="form-control form-control-sm" style="width: 80px;"
                                    value="{{ $channels->perPage() }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <span style="color: var(--text-secondary); font-size: 0.875rem;">entries</span>
                            </form>
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
                                <th style="width: 20%;">Region</th>
                                <th style="width: 25%;">Country</th>
                                <th style="width: 35%;">Name</th>
                                <th style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($channels as $channel)
                                <tr>
                                    <td>
                                        <span class="badge badge-info" style="font-size: 0.75rem;">{{ $channel->region }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $countryData = collect($countries)->firstWhere(
                                                'code',
                                                strtoupper($channel->country),
                                            );
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($countryData)
                                                <img src="{{ asset('assets/dist/images/flags/' . strtolower($countryData['code']) . '.svg') }}"
                                                    width="28" height="20" alt="{{ $countryData['name'] }}" style="border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                                <span style="font-weight: 500;">{{ $countryData['name'] }}</span>
                                            @else
                                                <span>{{ $channel->country }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="font-weight: 500;">{{ $channel->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editChannelModal{{ $channel->id }}" style="font-size: 0.8125rem;">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.channels.destroy', $channel->id) }}"
                                                method="POST" style="margin: 0;"
                                                onsubmit="return confirm('Are you sure you want to delete this channel?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" style="font-size: 0.8125rem;">Delete</button>
                                            </form>
                                        </div>

                                                <!-- Edit Modal -->
                                                <div class="modal modal-blur fade" id="editChannelModal{{ $channel->id }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <form method="POST"
                                                            action="{{ route('admin.channels.update', $channel->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit Channel</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Region</label>
                                                                        <select name="region" class="form-select" required>
                                                                            @foreach (['Europe', 'Americas', 'Arabic', 'Africa', 'Australia', 'Asia'] as $region)
                                                                                <option value="{{ $region }}"
                                                                                    {{ $channel->region === $region ? 'selected' : '' }}>
                                                                                    {{ $region }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Country</label>
                                                                        <select class="form-select" name="country" required>
                                                                            <option value="">Select Country</option>
                                                                            @foreach ($countries as $country)
                                                                                <option
                                                                                    value="{{ strtolower($country['code']) }}"
                                                                                    {{ strtolower($channel->country) == strtolower($country['code']) ? 'selected' : '' }}>
                                                                                    {{ $country['name'] }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Name</label>
                                                                        <input type="text" name="name"
                                                                            class="form-control"
                                                                            value="{{ $channel->name }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-success">Update</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color);">
                            @include('admin.partials.pagination', ['paginator' => $channels])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Channel Modal -->
    <div class="modal modal-blur fade" id="addChannelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('admin.channels.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Channel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Region</label>
                            <select name="region" class="form-select" required>
                                <option value="">Select Region</option>
                                @foreach (['Europe', 'Americas', 'Arabic', 'Africa', 'Australia', 'Asia'] as $region)
                                    <option value="{{ $region }}">{{ $region }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <select class="form-select" name="country" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ strtolower($country['code']) }}"
                                        {{ old('country') == strtolower($country['code']) ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Channel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
