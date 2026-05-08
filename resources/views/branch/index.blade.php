<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Branch<span
                            class="badge badge-soft-primary border border-primary page-header-badge ms-2">Total Branch :
                            5</span></h4>
                </div>
                <div class="text-end d-flex">
                    <div class="dropdown me-1">
                        <a href="javascript:void(0);"
                            class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li><a class="dropdown-item" href="#">Download as PDF</a></li>
                            <li><a class="dropdown-item" href="#">Download as Excel</a></li>
                        </ul>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add New Branch</a>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="search-set">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);"
                            class="btn btn-white bg-white fs-14 py-1 border d-inline-flex text-dark align-items-center"
                            data-bs-toggle="dropdown">
                            <i class="ti ti-filter text-gray-5 me-1"></i>Filters
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width:280px;">
                            <div class="mb-3">
                                <label class="form-label">Branch</label>
                                <select class="select">
                                    <option>Select</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-0 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light btn-sm">Reset</button>
                                <button type="button" class="btn btn-primary btn-sm">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table datatable mb-0">
                            <thead>
                                <tr>
                                    <th>Branch Name</th>
                                    {{-- <th>City</th> --}}
                                    <th>Pincode</th>
                                    {{-- <th>Manager</th> --}}
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr>
                                        <td>{{ $branch->Branchname }}</td>
                                        {{-- <td>{{ $branch->ERPLocation ?? '-' }}</td> --}}
                                        <td>{{ $branch->LocId ?? '-' }}</td>
                                        {{-- <td>{{ $branch->CreatedBy ?? '-' }}</td> --}}
                                        <td>
                                            @if ($branch->Status == 0)
                                                <span class="badge badge-soft-success border border-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-warning border border-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-item">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2">
                                                    <li>
                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#edit_modal">
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#delete_modal">
                                                            Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>


                </div>

            </div>
            <div class="table-footer-bar d-flex justify-content-between align-items-center">
                <div>
                    Row Per Page
                    <select id="perPage" class="form-select form-select-sm d-inline-block" style="width:70px;">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    Entries
                </div>

                <!-- Pagination -->
                <div class="pagination-box">
                    {{ $branches->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
                </div>

            </div>
            <script>
                document.getElementById('perPage').addEventListener('change', function() {
                    let perPage = this.value;
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                });
            </script>
            <div id="add_modal" class="modal fade">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="text-dark modal-title fw-bold">Add New Branch</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>
                        <form action="branch.html">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Branch Name<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter branch name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter city">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pincode<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter pincode">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Manager<span class="text-danger ms-1">*</span></label>
                                    <select class="select">
                                        <option>Murali</option>
                                        <option>Pallavi A</option>
                                        <option>Haseena</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                    <select class="select">
                                        <option>Active</option>
                                        <option>Pending</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add New Branch</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="edit_modal" class="modal fade">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="text-dark modal-title fw-bold">Edit Branch</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>
                        <form action="branch.html">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Branch Name<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter branch name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter city">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pincode<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter pincode">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Manager<span class="text-danger ms-1">*</span></label>
                                    <select class="select">
                                        <option>Murali</option>
                                        <option>Pallavi A</option>
                                        <option>Haseena</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                    <select class="select">
                                        <option>Active</option>
                                        <option>Pending</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete_modal">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <span class="avatar avatar-xl bg-danger-transparent rounded-circle text-danger">
                                    <i class="ti ti-trash fs-24"></i>
                                </span>
                            </div>
                            <h5 class="mb-2">Delete Branch</h5>
                            <p class="mb-3">Are you sure you want to delete this branch record?</p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">Copyright &copy; 2026 - Vecura.</p>
        </div>
    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
