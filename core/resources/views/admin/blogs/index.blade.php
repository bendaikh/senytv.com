@extends('admin.layouts.master')

@section('title', 'All Blog Posts')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">Content Management</div>
            <h2>Blog Posts</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                New Article
            </a>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Filters -->
            <div class="card-modern mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.blogs.index') }}">
                        <div class="row g-3">
                            <!-- Language Selection -->
                            <div class="col-12 col-md-4">
                                @include('admin.partials.forms.select-language', [
                                    'languages' => $languages,
                                    'languageNames' => $languageNames,
                                    'name' => 'language',
                                    'value' => request('language'),
                                    'label' => 'Select Language',
                                ])
                            </div>

                            <!-- Status Selection -->
                            <div class="col-12 col-md-4">
                                <label class="form-label">Select Status</label>
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>

                            <!-- Search Field -->
                            <div class="col-12 col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="Search by title" onkeydown="if(event.keyCode == 13) this.form.submit();">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="row g-4 mb-4">
                @foreach ($blogs as $blog)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card-modern" style="overflow: hidden; position: relative;">
                            @if ($blog->status == 'published')
                                <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                    <span class="badge badge-success">Published</span>
                                </div>
                            @else
                                <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                    <span class="badge badge-danger">Draft</span>
                                </div>
                            @endif
                            <div style="height: 200px; overflow: hidden; background: var(--bg-primary);">
                                <img src="{{ asset($blog->image) }}" alt="{{ $blog->translations->first()->title }}"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body">
                                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $blog->translations->first()->title }}
                                </h3>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning" style="flex: 1; font-size: 0.875rem;">Edit</a>
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="flex: 1; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="width: 100%; font-size: 0.875rem;"
                                            onclick="return confirm('Are you sure you want to delete this blog post?')">Delete</button>
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
