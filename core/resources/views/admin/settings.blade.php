@extends('admin.layouts.master')

@section('title', 'System Settings')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">System Configuration</div>
            <h2>System Settings</h2>
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
            <div class="row row-cards">
                <div class="col-12 col-md-8 mb-3">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="card-modern"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4 class="card-title">Settings</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-5">
                                <div class="col-12">
                                    <div class="row">
                                        <!-- Site Name -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Site Name:</label>
                                                <input type="text" class="form-control" name="site_name"
                                                    value="{{ old('site_name', $settings['site_name']) }}">

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Sitemap URL:</label>
                                                <input type="text" class="form-control" value="{{ url('/sitemap.xml') }}"
                                                    disabled>
                                                <small class="form-text text-muted">
                                                    Submit this URL to Google Search Console or Bing Webmaster Tools.
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Active Template -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Select Theme</label>
                                                <select class="form-select" name="active_template">
                                                    @foreach ($templates as $templateName)
                                                        <option value="{{ $templateName }}"
                                                            {{ old('active_template', $settings['active_template'] ?? '') == $templateName ? 'selected' : '' }}>
                                                            {{ ucfirst($templateName) }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <!-- Site Logo -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Site Logo:</label>
                                                <input type="file" class="form-control" name="site_logo">

                                            </div>
                                        </div>

                                        <!-- Site Favicon -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Favicon:</label>
                                                <input type="file" class="form-control" name="site_favicon">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="hr-text my-4 text-cyan">Other Settings</div>
                                    <div class="row">
                                        <!-- Google Analytics -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Google Analytics ID:</label>
                                                <input type="text" class="form-control" name="google_analytics"
                                                    value="{{ old('google_analytics', $settings['google_analytics']) }}">

                                            </div>
                                        </div>



                                        <!-- WhatsApp -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">WhatsApp Number:</label>
                                                <input type="text" class="form-control" name="whatsapp"
                                                    value="{{ old('whatsapp', $settings['whatsapp']) }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">WhatsApp direction:</label>
                                                <select class="form-select" name="whatsapp_dir">
                                                    <option value="left"
                                                        {{ old('whatsapp_dir', $settings['whatsapp_dir']) == 'left' ? 'selected' : '' }}>
                                                        Left</option>
                                                    <option value="center"
                                                        {{ old('whatsapp_dir', $settings['whatsapp_dir']) == 'center' ? 'selected' : '' }}>
                                                        Center</option>
                                                    <option value="right"
                                                        {{ old('whatsapp_dir', $settings['whatsapp_dir']) == 'right' ? 'selected' : '' }}>
                                                        Right</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">WhatsApp Text:</label>
                                                <textarea class="form-control" name="whatsapp_text" rows="3">{{ old('whatsapp_text', $settings['whatsapp_text']) }}</textarea>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Custom Scripts (JS/Chat/Tracking):</label>
                                                <textarea class="form-control" name="custom_scripts" rows="6"
                                                    placeholder="Paste your script tags or JS code here...">{{ old('custom_scripts', $settings['custom_scripts']) }}</textarea>
                                                <small class="form-text text-muted">
                                                    You can paste custom JavaScript code here (e.g. live chat, analytics,
                                                    widget scripts). Make sure it's safe and valid.
                                                </small>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-dark">Save Settings</button>
                        </div>
                    </form>

                </div>
                <div class="col-12 col-md-4 mb-3">
                    <form method="POST" action="{{ route('admin.social-media.store') }}" enctype="multipart/form-data"
                        class="card mb-3">
                        @csrf
                        <div class="card-header">
                            <h4 class="card-title">Add Social Media Link</h4>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label" for="name">Social Media Name</label>
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Enter social media name" value="{{ old('name') }}" required />
                                @error('name')
                                    <div class="form-feedback text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="url">Social Media URL</label>
                                <input type="url" id="url" class="form-control" name="url"
                                    placeholder="Enter social media URL" value="{{ old('url') }}" required />
                                @error('url')
                                    <div class="form-feedback text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="icon">Icon (jpg, png, svg, webp)</label>
                                <input type="file" id="icon" class="form-control" name="icon"
                                    accept=".jpg,.jpeg,.png,.svg,.webp" required />
                                @error('icon')
                                    <div class="form-feedback text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Add Social Media Link</button>

                        </div>
                    </form>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">Social Media Links</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Link</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $socialLinks = is_string($settings['social_media_links'] ?? null)
                                            ? json_decode($settings['social_media_links'], true)
                                            : $settings['social_media_links'] ?? [];
                                    @endphp

                                    @forelse ($socialLinks as $index => $social)
                                        <tr>
                                            <td>
                                                <div class="d-flex py-1 align-items-center">
                                                    <span class="avatar avatar-2 me-3"
                                                        style="background-image: url('{{ url($social['icon']) }}')"></span>
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium text-capitalize">
                                                            {{ $social['name'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ $social['url'] }}" target="_blank"
                                                    class="text-primary text-truncate d-block">
                                                    {{ strlen($social['url']) > 10 ? substr($social['url'], 0, 10) . '...' : $social['url'] }}
                                                </a>
                                            </td>

                                            <td class="text-end">
                                                <form action="{{ route('admin.social-media.destroy', $index) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this social media link?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted fst-italic">No social media
                                                links added yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
