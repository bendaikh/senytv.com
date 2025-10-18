@extends('admin.layouts.master')
@section('title', 'Members Directory')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <h2 class="page-title mb-0">Active Members Directory</h2>
                </div>
            </div>

        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if ($clients->total() > 0)
                <div class="row row-cards mb-3">
                    @foreach ($clients as $client)
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <a href="{{ route('admin.clients.show', ['client' => $client->id]) }}">
                                    <div class="card-body p-4 text-center">
                                        <span class="avatar avatar-xl mb-3 rounded"
                                            style="background-image: url({{ asset('assets/dist/images/flags/' . $client->country . '.svg') }});border-radius:50%!important">
                                        </span>
                                        <h3 class="m-0 mb-1">{{ $client->full_name }}</h3>
                                        <div class="text-secondary">{{ $client->email }}</div>
                                       
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @include('admin.partials.pagination', ['paginator' => $clients])
            @endif
        </div>
    </div>
@endsection
