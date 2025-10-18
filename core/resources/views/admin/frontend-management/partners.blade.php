@extends('admin.layouts.master')

@section('title', 'Partners Management')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Partners Management</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                                + Add Partner
                            </button>
                        </div>

                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-secondary">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <form method="GET">
                                            <input type="number" name="perPage" class="form-control form-control-sm"
                                                id="paginationInput" value="{{ $partners->perPage() }}"
                                                aria-label="partners count">
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        </form>
                                    </div>
                                    entries
                                </div>
                                <div class="ms-auto text-secondary">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <form method="GET">
                                            <input type="text" name="search" class="form-control form-control-sm"
                                                aria-label="Search partners" placeholder="Search..."
                                                value="{{ request('search') }}">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Alt Text</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partners as $partner)
                                        <tr>
                                            <td>
                                                <img src="{{ asset($partner->logo) }}"
                                                    alt="{{ $partner->alt ?? $partner->name }}" width="50"
                                                    height="50" class="img-fluid">
                                            </td>
                                            <td>{{ $partner->name }}</td>
                                            <td>{{ $partner->alt ?? '-' }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <button class="btn btn-1" data-bs-toggle="modal"
                                                        data-bs-target="#editPartnerModal{{ $partner->id }}">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                                        method="POST" class="d-inline-block"
                                                        onsubmit="return confirm('Are you sure you want to delete this partner?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-1 btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Partner Modal -->
                                        <div class="modal modal-blur fade" id="editPartnerModal{{ $partner->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ route('admin.partners.update', $partner->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Partner</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="name">Name</label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control"
                                                                    value="{{ old('name', $partner->name) }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label" for="alt">Alt Text
                                                                    (optional)
                                                                </label>
                                                                <input type="text" name="alt" id="alt"
                                                                    class="form-control"
                                                                    value="{{ old('alt', $partner->alt) }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label" for="logo">Logo (leave blank
                                                                    to keep current)</label>
                                                                <input type="file" name="logo" id="logo"
                                                                    class="form-control" accept="image/*">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @include('admin.partials.pagination', ['paginator' => $partners])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Partner Modal -->
    <div class="modal modal-blur fade" id="addPartnerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Partner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="alt">Alt Text (optional)</label>
                            <input type="text" name="alt" id="alt" class="form-control"
                                value="{{ old('alt') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="logo">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Partner</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
