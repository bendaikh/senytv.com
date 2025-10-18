@extends('admin.layouts.master')

@section('title', "Manage Languages")

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

            <!-- Form to select language and file -->
            <form method="GET" action="{{ route('admin.languages.index') }}" class="mb-4">
                <div class="row">
                    <!-- Language Selection -->
                    <div class="col-12 col-md-4 col-lg-3">
                        @include('admin.partials.forms.select-language', [
                            'languages' => $languages,
                            'languageNames' => $languageNames,
                            'name' => 'language',
                            'value' => request('language'),
                            'label' => 'Select Language',
                        ])
                    </div>

                    <!-- File Selection -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label">Select File</label>
                        <select class="form-select" name="file" id="file" onchange="this.form.submit()">
                            @foreach ($files as $availableFile)
                                <option value="{{ $availableFile }}" {{ $availableFile == $file ? 'selected' : '' }}>
                                    {{ ucfirst($availableFile) }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label">Results per page</label>
                        <select class="form-select" name="per_page" onchange="this.form.submit()">
                            @foreach ([5, 10, 20, 50] as $size)
                                <option value="{{ $size }}"
                                    {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Field -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Search key or value..." onkeydown="if(event.keyCode == 13) this.form.submit();">
                    </div>
                </div>

            </form>

            <!-- Language File Editing Form -->
            <form method="POST" action="{{ route('admin.languages.update', ['language' => $lang, 'file' => $file]) }}">
                @csrf
                @method('PUT')
               
               
                <div class="col-12">
                    <div class="card">
                        <div class="card-header sticky-top bg-white">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h3 class="card-title mb-0">Edit Language File: {{ ucfirst($file) }}</h3>

                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Key</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paginatedContent as $key => $value)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>
                                                <input type="text" name="content[{{ $key }}]"
                                                    class="form-control" value="{{ old('content.' . $key, $value) }}" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>

                        <!-- Pagination -->
                        @include('admin.partials.pagination', ['paginator' => $paginatedContent])

                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
