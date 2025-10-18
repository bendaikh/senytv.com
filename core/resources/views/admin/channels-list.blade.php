@extends('admin.layouts.master')

@section('title', 'Channels List')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">All Channels</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChannelModal">
                                + Add Channel
                            </button>
                        </div>

                        <div class="card-body border-bottom py-3">
                            <div class="d-flex flex-wrap justify-content-between">
                                <form method="GET" class="d-flex align-items-center gap-2">
                                    <label class="form-label m-0">Show</label>
                                    <input type="number" name="perPage" class="form-control form-control-sm w-75"
                                        value="{{ $channels->perPage() }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                </form>
                                <form method="GET" class="d-flex align-items-center gap-2">
                                    <label class="form-label m-0">Search:</label>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Search..." value="{{ request('search') }}">
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered align-middle">
                                <thead class="thead-light">
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
                                            <td><span class="badge bg-blue-lt">{{ $channel->region }}</span></td>
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
                                                            width="24" height="18" alt="{{ $countryData['name'] }}">
                                                        <span>{{ $countryData['name'] }}</span>
                                                    @else
                                                        <span>{{ $channel->country }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $channel->name }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a href="#" class="btn btn-1" data-bs-toggle="modal"
                                                        data-bs-target="#editChannelModal{{ $channel->id }}">
                                                        Edit
                                                    </a>

                                                    <form action="{{ route('admin.channels.destroy', $channel->id) }}"
                                                        method="POST" class="d-inline-block"
                                                        onsubmit="return confirm('Are you sure you want to delete this channel?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-1 btn-danger">Delete</button>
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

                        @include('admin.partials.pagination', ['paginator' => $channels])
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
