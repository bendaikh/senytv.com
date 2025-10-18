@extends('admin.layouts.master')
@section('title', 'Plans')

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

            <!-- Language Filter Form -->
            <div class="col-12 col-md-4 mb-3 ms-auto">
                <form method="GET" action="{{ route('admin.plans.index') }}">
                    <select name="language" class="form-select" onchange="this.form.submit()">
                        <option value="">All Languages</option>
                        @foreach ($languages as $langCode)
                            <option value="{{ $langCode }}" {{ request('language') == $langCode ? 'selected' : '' }}>
                                {{ $languageNames[$langCode] ?? strtoupper($langCode) }}
                                ({{ strtoupper($langCode) }})
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Plans List -->
            <div class="row row-cards">
                @foreach ($plans as $plan)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-md">
                            @if ($plan->best_plan == 1)
                                <div class="ribbon ribbon-top ribbon-bookmark bg-green">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-3">
                                        <path
                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            <div class="card-body text-center">
                                <div class="text-uppercase text-secondary font-weight-medium">{{ $plan->name }}</div>
                                <div class="display-5 fw-bold my-3">${{ $plan->price }}</div>
                                <ul class="list-unstyled lh-lg">
                                    @foreach (explode("\n", $plan->description) as $feature)
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon me-1 text-success icon-2">
                                                <path d="M5 12l5 5l10 -10"></path>
                                            </svg>{{ trim($feature) }}
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="d-flex gap-2 text-center mt-4">
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-green w-50">Edit</a>
                                    <a href="#" class="btn btn-red w-50">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('admin.partials.pagination', ['paginator' => $plans])
        </div>
    </div>
@endsection
