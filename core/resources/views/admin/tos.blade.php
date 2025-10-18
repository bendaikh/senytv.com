@extends('admin.layouts.master')

@section('title', 'Tos Management')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/dist/libs/summernote/summernote-lite.min.css') }}">
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">TOS Management</h2>
                    </div>
                </div>
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
                {{-- Privacy Policy --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Privacy Policy</h4>
                        <form method="post" action="{{ route('admin.tos.privacy.save') }}">
                            @csrf
                            <div class="mb-3">
                                <textarea id="privacy-policy-summernote" name="privacy_policy">{{ old('privacy_policy', $tos->privacy_policy) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Privacy Policy</button>
                        </form>
                    </div>
                </div>

                {{-- Terms of Use --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Terms of Use</h4>
                        <form method="post" action="{{ route('admin.tos.terms.save') }}">
                            @csrf
                            <div class="mb-3">
                                <textarea id="terms-of-use-summernote" name="terms_of_use">{{ old('terms_of_use', $tos->terms_of_use) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Terms of Use</button>
                        </form>
                    </div>
                </div>

                {{-- Refund Policy --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Refund Policy</h4>
                        <form method="post" action="{{ route('admin.tos.refund.save') }}">
                            @csrf
                            <div class="mb-3">
                                <textarea id="refund-policy-summernote" name="refund_policy">{{ old('refund_policy', $tos->refund_policy) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Refund Policy</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('assets/dist/libs/summernote/summernote-lite.min.js') }}"></script>
    <script>
        function initializeSummernote(selector, placeholder) {
            $(selector).summernote({
                placeholder: placeholder,
                tabsize: 2,
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }

        $(document).ready(function() {
            initializeSummernote('#privacy-policy-summernote', 'Enter privacy policy');
            initializeSummernote('#terms-of-use-summernote', 'Enter terms of use');
            initializeSummernote('#refund-policy-summernote', 'Enter refund policy');
        });
    </script>
@endsection
