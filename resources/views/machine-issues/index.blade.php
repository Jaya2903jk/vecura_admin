<?php $page = 'staff'; ?>
@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-tool text-primary fs-24"></i>Machine Issues Master
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Machine Issues: {{ $MachineIssues->total() }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage machine issues, linked equipment, requirement types, and status.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'machine-issues'))
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#add_modal">
                            <i class="ti ti-plus me-1"></i>New Machine Issue
                        </button>
                    @endif
                </div>
            </div>

            <!-- FILTER BAR -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <form method="GET" action="{{ route('machine-issues.index') }}" id="machineIssuesFilterForm">
                        <div class="row g-2 align-items-center">
                            {{-- Search Input --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Search Machine Issue</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 fs-13 text-dark"
                                        placeholder="Issue Name..." value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('machineIssuesFilterForm').submit()">
                                    <option value="">All Status</option>
                                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            {{-- Action Controls --}}
                            <div class="col-md-3 d-flex align-items-end gap-1 pt-3">
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold fs-13 flex-fill">
                                    <i class="ti ti-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('machine-issues.index') }}" class="btn btn-light border btn-sm fw-semibold fs-13 text-secondary" title="Reset Filters">
                                    <i class="ti ti-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MACHINE ISSUES TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">Issue Name</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Linked Machine</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Description</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Type</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($MachineIssues as $cat)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="patient-avatar-circle flex-shrink-0 text-decoration-none">
                                                    <i class="ti ti-tool fs-16 text-primary"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark fs-13">{{ $cat->IssuesName }}</span>
                                                    <div class="fs-11 text-muted">ID: #{{ $cat->machineIssueId }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1 fs-12 fw-semibold rounded-2">
                                                <i class="ti ti-cpu me-1 text-muted"></i>{{ $cat->Machine->MachineName ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="fs-13 text-secondary">
                                            {{ $cat->Description ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill text-capitalize">
                                                {{ $cat->Type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($cat->Status == 'Active')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-point-filled me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-point-filled me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-tools-off fs-36 text-muted mb-2 d-block"></i>
                                            No machine issues found matching criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TABLE FOOTER / PAGINATION BAR -->
            <div class="table-footer-bar d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 fs-13 bg-white p-3 rounded-3 shadow-xs border gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <span class="text-muted fw-medium">Rows per page:</span>
                        <select id="perPageDept" class="form-select form-select-sm d-inline-block border ms-1 fw-bold text-dark" style="width:75px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $MachineIssues->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $MachineIssues->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $MachineIssues->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$MachineIssues" :append="['per_page' => $perPage, 'search' => request('search'), 'status' => request('status')]" />
                </div>
            </div>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="add_modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-tool text-primary fs-20"></i>Add New Machine Issue
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="MachineIssuesForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Issue Name <span class="text-danger">*</span></label>
                            <input type="text" name="issues_name" id="issues_name" class="form-control fs-13"
                                placeholder="Enter issue name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Linked Machine <span class="text-danger">*</span></label>
                            <select name="machine_id" class="form-select fs-13" required>
                                <option value="">Select Machine</option>
                                @foreach ($machines as $machine)
                                    <option value="{{ $machine->MachineId }}">
                                        {{ $machine->MachineName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select fs-13" required>
                                <option value="spare">Spare Required</option>
                                <option value="component">Component Required</option>
                                <option value="service">Service Issues</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select fs-13" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="ti ti-plus me-1"></i>Save Machine Issue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('perPageDept').addEventListener('change', function() {
            let perPage = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });

        $(document).ready(function() {
            $('#MachineIssuesForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#submitBtn');

                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ url('machine-issues/store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        submitBtn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i>Save Machine Issue');

                        if (response.status) {
                            $('#add_modal').modal('hide');
                            form.reset();

                            Swal.fire({
                                icon: "success",
                                title: "Machine Issue Created Successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },

                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i>Save Machine Issue');

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');

                                if (input.next('.invalid-feedback').length) {
                                    input.next('.invalid-feedback').text(value[0]);
                                } else {
                                    input.after(
                                        '<div class="invalid-feedback dynamic">' +
                                        value[0] + '</div>'
                                    );
                                }
                            });

                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);

                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong!",
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
