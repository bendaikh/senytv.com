@extends('admin.layouts.master')
@section('title', 'Manage FAQs')

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
            <div class="row">
                <!-- Left Column: Add/Edit FAQ -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($editFaq) ? 'Edit FAQ' : 'Add New FAQ' }}</h3>
                        </div>
                        <div class="card-body">
                            <form
                                action="{{ isset($editFaq) ? route('admin.faqs.update', $editFaq->id) : route('admin.faqs.store') }}"
                                method="POST">
                                @csrf
                                @if (isset($editFaq))
                                    @method('PUT')
                                @endif

                                <div class="mb-3">
                                    @include('admin.partials.forms.select-language', [
                                        'languages' => $languages,
                                        'languageNames' => $languageNames,
                                        'name' => 'language',
                                        'value' => $editFaq->language ?? null,
                                        'label' => 'Select Language'
                                    ])                                    
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Question</label>
                                    <input type="text" class="form-control" name="question" value="{{ old('question', $editFaq->question ?? '') }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Answer</label>
                                    <textarea class="form-control" name="answer" rows="4" required>{{ old('answer', $editFaq->answer ?? '') }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Order (Optional)</label>
                                    <input type="number" class="form-control" name="order" value="{{ old('order', $editFaq->order ?? '') }}">
                                </div>
                                

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">{{ isset($editFaq) ? 'Update' : 'Add' }}
                                        FAQ</button>
                                    @if (isset($editFaq))
                                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancel</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: List FAQs -->
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">All FAQs</h3>
                            <form method="GET" action="{{ route('admin.faqs.index') }}">
                                <select name="language" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Languages</option>
                                    @foreach ($languages as $langCode)
                                        <option value="{{ $langCode }}"
                                            {{ request('language') == $langCode ? 'selected' : '' }}>
                                            {{ $languageNames[$langCode] ?? strtoupper($langCode) }}
                                            ({{ strtoupper($langCode) }})
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Language</th>
                                            <th>Question</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($faqs as $faq)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $faq->language_name }} ({{ strtoupper($faq->language) }})</td>
                                                <td>{{ $faq->question }}</td>
                                                <td>
                                                    <div class="btn-list flex-nowrap">
                                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                                            class="btn btn-1"> Edit </a>
                                                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-red"
                                                                onclick="return confirm('Are you sure you want to delete this FAQ?')">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No FAQs found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @include('admin.partials.pagination', ['paginator' => $faqs])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
