@extends('admin.layouts.master')

@section('title', 'Color Setup')

@section('content')
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="page-header-title">
            <div class="page-pretitle">System Configuration</div>
            <h2>Color Setup</h2>
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
                    <form action="{{ route('admin.color-setup.update') }}" method="POST" class="card-modern">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4 class="card-title">Website Primary Color</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-5">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Primary Color:</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" 
                                                name="primary_color" id="primary_color" 
                                                value="{{ old('primary_color', $primaryColor) }}" 
                                                style="width: 80px; height: 50px; cursor: pointer;">
                                            <input type="text" class="form-control" 
                                                id="primary_color_text" 
                                                value="{{ old('primary_color', $primaryColor) }}" 
                                                placeholder="#ffbf23"
                                                pattern="^#[a-fA-F0-9]{6}$"
                                                maxlength="7">
                                        </div>
                                        <small class="form-text text-muted">
                                            Select the primary color for your website. This color will be used for buttons, links, icons, and other accent elements throughout the site.
                                        </small>
                                        @error('primary_color')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Preview:</label>
                                        <div class="border rounded p-4" style="background-color: #f8f9fa;">
                                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                                <button type="button" class="btn" 
                                                    style="background-color: {{ $primaryColor }}; color: white; border: none;"
                                                    id="preview-btn">
                                                    Button Preview
                                                </button>
                                                <a href="#" style="color: {{ $primaryColor }}; text-decoration: none; font-weight: 600;" 
                                                    id="preview-link">
                                                    Link Preview
                                                </a>
                                                <div class="d-inline-block p-2 rounded" 
                                                    style="background-color: {{ $primaryColor }}; color: white;"
                                                    id="preview-badge">
                                                    Badge
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-dark">Save Color</button>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Color Information</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                The primary color you choose here will be applied to:
                            </p>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                    Buttons and CTAs
                                </li>
                                <li class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                    Links and hover states
                                </li>
                                <li class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                    Icons and accents
                                </li>
                                <li class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                    Background gradients
                                </li>
                                <li class="mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                    Borders and highlights
                                </li>
                            </ul>
                            <div class="alert alert-info mt-3">
                                <small>
                                    <strong>Tip:</strong> Choose a color that complements your brand and provides good contrast for readability.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorPicker = document.getElementById('primary_color');
            const colorText = document.getElementById('primary_color_text');
            const previewBtn = document.getElementById('preview-btn');
            const previewLink = document.getElementById('preview-link');
            const previewBadge = document.getElementById('preview-badge');

            // Sync color picker with text input
            colorPicker.addEventListener('input', function() {
                colorText.value = this.value;
                updatePreview(this.value);
            });

            // Sync text input with color picker
            colorText.addEventListener('input', function() {
                const value = this.value;
                if (/^#[a-fA-F0-9]{6}$/.test(value)) {
                    colorPicker.value = value;
                    updatePreview(value);
                }
            });

            function updatePreview(color) {
                previewBtn.style.backgroundColor = color;
                previewLink.style.color = color;
                previewBadge.style.backgroundColor = color;
            }
        });
    </script>
@endsection

