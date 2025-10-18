@extends('admin.layouts.master')
@section('title', 'Add Plan')


@section('content')
    <div class="page-body">
        <div class="container-xl">
            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif
            <div class="row row-cards">
                <div class="card px-0">
                    <div class="col-12">
                        <form method="POST" action="{{ route('admin.plans.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header bg-dark-lt h3 text-dark bold pt-2 pb-2">
                                Add a Plan
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Plan Name -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Plan Name:</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Enter plan name" value="{{ old('name') }}" required />
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Plan Price -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price ($):</label>
                                        <input type="number" step="0.01" class="form-control" name="price"
                                            placeholder="Enter price" value="{{ old('price') }}" required />
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Plan Duration -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Duration (Days):</label>
                                        <input type="number" class="form-control" name="duration"
                                            placeholder="Enter duration in days" value="{{ old('duration') }}" required />
                                        @error('duration')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3">
                                        @include('admin.partials.forms.select-language', [
                                            'languages' => $languages,
                                            'languageNames' => $languageNames,
                                            'name' => 'language',
                                            'value' => old('language'),
                                            'label' => 'Select Language',
                                        ])

                                    </div>

                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3">
                                        <label class="form-label">Is Best Plan?</label>
                                        <div class="form-selectgroup">
                                            <label class="form-selectgroup-item text-no-wrap">
                                                <input type="radio" name="best_plan" value="1"
                                                    class="form-selectgroup-input"
                                                    {{ old('best_plan', 0) == 1 ? 'checked' : '' }}>
                                                <span class="form-selectgroup-label">Yes</span>
                                            </label>

                                            <label class="form-selectgroup-item">
                                                <input type="radio" name="best_plan" value="0"
                                                    class="form-selectgroup-input"
                                                    {{ old('best_plan', 0) == 0 ? 'checked' : '' }}>
                                                <span class="form-selectgroup-label">No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Plan Description -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description (Enter each feature on a new line):</label>
                                        <textarea class="form-control" name="description" rows="5" placeholder="Enter each feature on a new line">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="d-flex flex-row-reverse mt-4">
                                    <button type="submit" class="btn btn-dark">Add Plan</button>
                                    <a href="{{ route('admin.plans.index') }}" class="btn btn-white me-4">Cancel</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
