@extends('admin.layouts.master')
@section('title', 'Images Manager')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Images Manager</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">

            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif

            <form action="{{ route('admin.images-manager.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row row-cards g-3">
                    @foreach ([1, 2, 3] as $i)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Slider {{ $i }}</h3>
                                </div>
                                <div class="card-body text-center">
                                    @if (!empty($images['img_slider_' . $i]))
                                        <img src="{{ url($images['img_slider_' . $i]) }}" alt="Slider {{ $i }}"
                                            class="img-fluid object-fit-contain border border-danger mb-3">
                                    @else
                                        <div class="text-muted mb-3">No image uploaded</div>
                                    @endif

                                    <input type="file" name="img_slider_{{ $i }}" class="form-control"
                                        accept="image/*">
                                    @error('img_slider_' . $i)
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Best Section Image</h3>
                            </div>
                            <div class="card-body text-center">
                                @if (!empty($images['best_section_img']))
                                    <img src="{{ url($images['best_section_img']) }}" alt="Best Section Image"
                                        class="img-fluid object-fit-contain border border-primary mb-3">
                                @else
                                    <div class="text-muted mb-3">No image uploaded</div>
                                @endif

                                <input type="file" name="best_section_img" class="form-control" accept="image/*">
                                @error('best_section_img')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                     <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Our Partners Logo</h3>
                            </div>
                            <div class="card-body text-center">
                                @if (!empty($images['our_partners_img']))
                                    <img src="{{ url($images['our_partners_img']) }}" alt="Best Section Image"
                                        class="img-fluid object-fit-contain border border-primary mb-3">
                                @else
                                    <div class="text-muted mb-3">No image uploaded</div>
                                @endif

                                <input type="file" name="our_partners_img" class="form-control" accept="image/*">
                                @error('our_partners_img')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">Update Images</button>
                </div>
            </form>

        </div>
    </div>
@endsection
