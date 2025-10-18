@extends('admin.layouts.master')
@section('title', 'Add Client')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @if (session('success'))
                @include('admin.partials.alerts.success', ['message' => session('success')])
            @endif
            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif
            <div class="row row-cards">
                <div class="card px-0">
                    <div class="col-12">
                        <form method="POST" action="{{ route('admin.clients.store') }}">
                            @csrf
                            <div class="card-header bg-dark-lt h3 text-dark bold pt-2 pb-2">
                                Add Client
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Full Name -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" name="full_name"
                                            placeholder="Enter full name" value="{{ old('full_name') }}" required />
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email:</label>
                                        <input type="email" class="form-control" name="email" placeholder="Enter email"
                                            value="{{ old('email') }}" required />
                                    </div>

                                    <!-- Phone Number (Optional) -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number (Optional):</label>
                                        <input type="text" class="form-control" name="phone_number"
                                            placeholder="Enter phone number" value="{{ old('phone_number') }}" />
                                    </div>

                                    <!-- Country -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Country:</label>
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

                                    <!-- IP Address (Optional) -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">IP Address (Optional):</label>
                                        <input type="text" class="form-control" name="ip"
                                            placeholder="Enter IP address" value="{{ old('ip') }}" />
                                    </div>

                                </div>

                                <div class="d-flex flex-row-reverse mt-4">
                                    <button type="submit" class="btn btn-dark">Add Client</button>
                                    <a href="{{ route('admin.clients.index') }}" class="btn btn-white me-4">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
