@extends('admin.layouts.master')
@section('title', 'Plans')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Subscription Management</div>
            <h2>Service Plans</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <form method="GET" action="{{ route('admin.plans.index') }}" style="margin: 0;">
                <select name="language" class="form-select" onchange="this.form.submit()" style="min-width: 200px;">
                    <option value="">All Languages</option>
                    @foreach ($languages as $langCode)
                        <option value="{{ $langCode }}" {{ request('language') == $langCode ? 'selected' : '' }}>
                            {{ $languageNames[$langCode] ?? strtoupper($langCode) }} ({{ strtoupper($langCode) }})
                        </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                New Plan
            </a>
        </div>
    </div>

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

            <!-- Plans List -->
            <div class="row g-4 mb-4">
                @foreach ($plans as $plan)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card-modern" style="position: relative; height: 100%;">
                            @if ($plan->best_plan == 1)
                                <div style="position: absolute; top: -10px; right: 20px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3); z-index: 10;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none" style="margin-right: 0.25rem; vertical-align: middle;">
                                        <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                                    </svg>
                                    Best
                                </div>
                            @endif
                            <div class="card-body text-center" style="padding: 2rem 1.5rem;">
                                <div style="text-transform: uppercase; color: var(--text-secondary); font-weight: 600; font-size: 0.875rem; letter-spacing: 0.05em; margin-bottom: 1rem;">{{ $plan->name }}</div>
                                <div style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin: 1.5rem 0;">
                                    ${{ $plan->price }}
                                </div>
                                <ul style="list-style: none; padding: 0; margin: 1.5rem 0; text-align: left;">
                                    @foreach (explode("\n", $plan->description) as $feature)
                                        <li style="margin: 0.75rem 0; display: flex; align-items: flex-start; font-size: 0.875rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="min-width: 18px; margin-right: 0.5rem; margin-top: 0.125rem; color: #11998e;">
                                                <path d="M5 12l5 5l10 -10"></path>
                                            </svg>
                                            <span>{{ trim($feature) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-success" style="flex: 1;">Edit</a>
                                    <button type="button" class="btn btn-danger" style="flex: 1;" onclick="if(confirm('Are you sure you want to delete this plan?')) { /* Add delete logic */ }">Delete</button>
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
