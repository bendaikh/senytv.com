@extends('admin.layouts.master')

@section('title', 'All Blog Posts')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row mb-3">
                <form method="GET" action="{{ route('admin.blogs.index') }}" class="mb-4">
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

                        <!-- Status Selection -->
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="form-label">Select Status</label>
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <!-- Search Field -->
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="Search by title" onkeydown="if(event.keyCode == 13) this.form.submit();">
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card">
                            <div class="card-status-top {{ $blog->status == 'published' ? 'bg-green' : 'bg-red' }}"></div>
                            <div class="card-header">
                                <h3 class="card-title">{{ $blog->translations->first()->title }}</h3>
                            </div>
                            <div class="card-body p-0">
                                <img src="{{ asset($blog->image) }}" class="img-fluid"
                                    alt="{{ $blog->translations->first()->title }}"
                                    style="height: 200px;width:100%;object-fit: cover; ">
                            </div>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                                        class="d-inline ms-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-primary"
                                            onclick="return confirm('Are you sure you want to delete this FAQ?')">Delete</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @include('admin.partials.pagination', ['paginator' => $blogs])
        </div>
    </div>
@endsection
