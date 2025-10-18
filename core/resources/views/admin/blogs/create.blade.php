@extends('admin.layouts.master')

@section('title', 'Add New Post')

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
                            <h3 class="card-title">Add New Post</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                @include('admin.partials.alerts.error', [
                                    'title' => 'There were some errors with your submission.',
                                ])
                            @endif

                            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Title & Slug Row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                placeholder="Enter post title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Slug (Optional)</label>
                                            <input type="text" name="slug" class="form-control"
                                                placeholder="Enter slug or leave empty">
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Editor -->
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea id="post-content" name="content" class="form-control"></textarea>
                                </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Sidebar) -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Post Settings</h3>
                        </div>
                        <div class="card-body">
                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label class="form-label">Post Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <!-- Alt Text for Image -->
                            <div class="mb-3">
                                <label class="form-label">Image Alt Text</label>
                                <input type="text" name="img_description" class="form-control" maxlength="125"
                                    placeholder="Enter alt text for the image" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Post Meta Description</label>
                                <textarea name="description" class="form-control" rows="4" maxlength="155" required></textarea>
                            </div>

                            <!-- Language Selection -->
                            <div class="mb-3">
                                @include('admin.partials.forms.select-language', [
                                    'languages' => $languages,
                                    'languageNames' => $languageNames,
                                    'name' => 'language',
                                    'value' => old('language'),
                                    'label' => 'Select Language',
                                ])
                            </div>



                            <!-- Status Selection -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>

                            <!-- Publish Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">Publish Post</button>
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
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
