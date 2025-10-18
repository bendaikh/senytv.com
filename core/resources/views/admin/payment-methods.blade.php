@extends('admin.layouts.master')
@section('title', 'Payment Methods')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            @if (session('success'))
                @include('admin.partials.alerts.success', ['message' => session('success')])
            @endif
            @if ($errors->any())
                @include('admin.partials.alerts.error', [
                    'title' => 'There were some errors with your submission.',
                ])
            @endif
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Payment Setup</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-xl-4 col-md-5 col-sm-12">
                    <form method="post" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header bg-dark-lt pt-3 pb-2">
                                <h4 class="text-dark">Add Payment Method</h4>
                            </div>
                            <div class="card-body pt-2 row">
                                <!-- Payment Method Name -->
                                <div class="mb-3">
                                    <label class="form-label">Payment Method Name</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name"
                                        placeholder="Bank Transfer" value="{{ old('name') }}">
                                </div>

                                <!-- Item Image -->
                                <div class="mb-3">
                                    <label class="form-label">Item Image</label>
                                    <input type="file" class="form-control {{ $errors->has('img') ? 'is-invalid' : '' }}"
                                        name="img" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml">
                                </div>
                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                        name="status">
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Disabled
                                        </option>
                                    </select>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Add Payment Method</button>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="col-lg-8 col-md-7 col-sm-12">
                    <div class="row">
                        @foreach ($methods as $method)
                            <div class="col-md-4 col-sm-12 mb-3">
                                <div class="card">
                                    <img src="{{ url($method->img) }}" class="fixed-img-height w-100 card-img-top"
                                        style="max-height: 170px; object-fit: contain;background-color: #bbbcbd;">
                                    <div class="card-body">
                                        <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit-{{ $method->id }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger w-100" data-bs-toggle="modal"
                                            data-bs-target="#modal-delete-{{ $method->id }}">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal modal-blur fade" id="modal-edit-{{ $method->id }}" tabindex="-1"
                                role="dialog" aria-hidden="true" aria-labelledby="modal-edit-title-{{ $method->id }}"
                                aria-describedby="modal-edit-description-{{ $method->id }}" aria-modal="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit {{ $method->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post"
                                            action="{{ route('admin.payment-methods.update', $method->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Payment Method Name</label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Amazon Gift Card"
                                                        value="{{ old('name', $method->name) }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Item Image</label>
                                                    <input type="file" class="form-control" name="img"
                                                        accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="1"
                                                            {{ $method->status == 1 ? 'selected' : '' }}>
                                                            Enabled</option>
                                                        <option value="0"
                                                            {{ $method->status == 0 ? 'selected' : '' }}>
                                                            Disabled</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <a href="#" class="btn"
                                                    data-bs-dismiss="modal">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary ms-auto">
                                                    Update Payment Method
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal modal-blur fade" id="modal-delete-{{ $method->id }}" tabindex="-1"
                                role="dialog" aria-hidden="true" aria-labelledby="modal-title-{{ $method->id }}"
                                aria-describedby="modal-description-{{ $method->id }}" aria-modal="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <div class="modal-status bg-danger"></div>
                                        <div class="modal-body text-center py-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                                                <path d="M12 9v4" />
                                                <path d="M12 17h.01" />
                                            </svg>
                                            <h3>Are you sure?</h3>
                                            <div class="text-secondary">Do you really want to delete the
                                                {{ $method->name }} category and all related items?</div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="w-100">
                                                <div class="row">
                                                    <div class="col">
                                                        <form method="post"
                                                            action="{{ route('admin.payment-methods.destroy', $method->id) }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-danger w-100"
                                                                data-bs-dismiss="modal">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="col"><a href="#" class="btn w-100"
                                                            data-bs-dismiss="modal">
                                                            Cancel
                                                        </a></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
