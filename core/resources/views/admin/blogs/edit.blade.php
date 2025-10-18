@extends('admin.layouts.master')

@section('title', 'Edit Post')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/dist/libs/summernote/summernote-lite.min.css') }}">
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <!-- Left Column (Main Content) -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Edit Post</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                @include('admin.partials.alerts.error', [
                                    'title' => 'There were some errors with your submission.',
                                ])
                            @endif

                            <form action="{{ route('admin.blogs.update', $blogPost->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Title & Slug Row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $blogPost->translations->first()->title }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Slug</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="{{ old('slug', $blogPost->translations->first()->slug) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Editor -->
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea id="post-content" name="content" class="form-control">{{ old('content', $blogPost->translations->first()->content) }}</textarea>
                                </div>

                        </div>
                    </div>
                </div>

                <!-- Right Column (Sidebar) -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>Post Settings</h3>
                        </div>
                        <div class="card-body">
                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label class="form-label">Post Image</label>
                                <input type="file" name="image" class="form-control">
                                @if ($blogPost->image)
                                    <div class="mt-2">
                                        <img src="{{ asset($blogPost->image) }}" alt="Post Image" width="100">
                                    </div>
                                @endif
                            </div>

                            <!-- Alt Text for Image -->
                            <div class="mb-3">
                                <label class="form-label">Image Alt Text</label>
                                <input type="text" name="img_description" class="form-control" maxlength="125"
                                    placeholder="Enter alt text for the image" value="{{ old('img_description', $blogPost->translations->first()->img_alt) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Post Meta Description</label>
                                <textarea name="description" class="form-control" rows="4" maxlength="155" required>{{ $blogPost->translations->first()->description }}</textarea>
                            </div>
                            

                            <!-- Language Selection -->
                            <div class="mb-3">
                                @include('admin.partials.forms.select-language', [
                                    'languages' => $languages,
                                    'languageNames' => $languageNames,
                                    'name' => 'language',
                                    'value' => old('language', $blogPost->translations->first()->language ?? ''),
                                    'label' => 'Select Language',
                                ])
                            </div>

                            <!-- Status Selection -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published"
                                        {{ old('status', $blogPost->status) == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                    <option value="draft"
                                        {{ old('status', $blogPost->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>

                            <!-- Publish Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">Update Post</button>
                            </div>
                        </div>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('assets/dist/libs/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#post-content').summernote({
                placeholder: 'Write your blog content here...',
                tabsize: 2,
                height: 400,
            });
        });
    </script>
@endsection
