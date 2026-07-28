@extends('layout.mainlayout')

@section('content')
    <style>
        .patient-code-tag {
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
            background-color: #f8fafc;
            padding: 3px 8px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            letter-spacing: 0.3px;
        }

        .patient-avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbeafe;
        }
    </style>

    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-users text-primary fs-24"></i>Patient Directory
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Patients: {{ $patients->total() }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage registered patients, personal records, medical histories, and consultation appointments.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold fs-13 text-secondary shadow-xs dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ti ti-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 fs-13 shadow-sm border-0">
                            <li>
                                <a class="dropdown-item rounded-1" href="{{ route('patient.export', ['format' => 'pdf'] + request()->query()) }}">
                                    <i class="ti ti-file-text me-2 text-danger"></i>Download as PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-1" href="{{ route('patient.export', ['format' => 'excel'] + request()->query()) }}">
                                    <i class="ti ti-file-spreadsheet me-2 text-success"></i>Download as Excel
                                </a>
                            </li>
                        </ul>
                    </div>

                    @hasPermission('create', 'patient')
                        <a href="{{ route('patient.create') }}" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs">
                            <i class="ti ti-user-plus me-1"></i>New Patient Registration
                        </a>
                    @endhasPermission
                </div>
            </div>

            <!-- SUCCESS ALERT -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-xs mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-circle-check fs-20 me-2 text-success"></i>
                        <span class="fs-13 fw-semibold">{{ $message }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- FILTER BAR -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <form method="GET" action="{{ route('patient.index') }}" id="patientFilterForm">
                        <div class="row g-2 align-items-center">
                            {{-- Search Input --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Search Patient</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 fs-13 text-dark"
                                        placeholder="Name / Reg No / Mobile / Email..." value="{{ $filters['search'] ?? '' }}">
                                </div>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('patientFilterForm').submit()">
                                    <option value="">All Status</option>
                                    @foreach ($filterOptions['statuses'] as $status)
                                        <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Treatment Status Filter --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Treatment Status</label>
                                <select name="treatment_joined" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('patientFilterForm').submit()">
                                    <option value="">All</option>
                                    <option value="Yes" {{ ($filters['treatment_joined'] ?? '') === 'Yes' ? 'selected' : '' }}>Joined</option>
                                    <option value="No" {{ ($filters['treatment_joined'] ?? '') === 'No' ? 'selected' : '' }}>Not Joined</option>
                                </select>
                            </div>

                            {{-- City Filter --}}
                            <div class="col-md-2">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">City</label>
                                <select name="city" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('patientFilterForm').submit()">
                                    <option value="">All Cities</option>
                                    @foreach ($filterOptions['cities'] as $city)
                                        <option value="{{ $city }}" {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Action Controls --}}
                            <div class="col-md-2 d-flex align-items-end gap-1 pt-3">
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold fs-13 flex-fill">
                                    <i class="ti ti-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('patient.index') }}" class="btn btn-light border btn-sm fw-semibold fs-13 text-secondary" title="Reset Filters">
                                    <i class="ti ti-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- PATIENT TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">Patient Info</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Registration No</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Contact Details</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Gender / Age</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">City / Branch</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Treatment</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($patients as $patient)
                                    @php
                                        $initials = strtoupper(substr($patient->FirstName ?? 'P', 0, 1) . substr($patient->LastName ?? '', 0, 1));
                                        $fullName = trim(($patient->FirstName ?? '') . ' ' . ($patient->LastName ?? ''));
                                        $status = trim($patient->CustomerStatus ?? 'Active');
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <a href="{{ route('patient.show', $patient->PatientID) }}" class="patient-avatar-circle flex-shrink-0 text-decoration-none">
                                                    {{ $initials ?: 'P' }}
                                                </a>
                                                <div>
                                                    <a href="{{ route('patient.show', $patient->PatientID) }}" class="fw-bold text-dark fs-13 text-decoration-none hover-primary">
                                                        {{ $fullName ?: 'Unnamed Patient' }}
                                                    </a>
                                                    <div class="fs-11 text-muted">ID: #{{ $patient->PatientID }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="patient-code-tag font-monospace">{{ $patient->RegistrationNo ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="fs-13 fw-semibold text-dark"><i class="ti ti-phone fs-12 text-muted me-1"></i>{{ $patient->Mobile ?: 'N/A' }}</div>
                                            <div class="fs-11 text-muted"><i class="ti ti-mail fs-11 me-1"></i>{{ $patient->EMail ?: '—' }}</div>
                                        </td>
                                        <td>
                                            <div class="fs-13 text-dark font-medium">{{ $patient->Gender ?: '—' }}</div>
                                            <div class="fs-11 text-muted">{{ $patient->DOB ? \Carbon\Carbon::parse($patient->DOB)->format('d-M-Y') : '—' }}</div>
                                        </td>
                                        <td>
                                            <div class="fs-13 text-dark fw-semibold">{{ $patient->CityName ?: '—' }}</div>
                                            <div class="fs-11 text-muted">{{ $patient->Loc_Id ?: 'ANR' }}</div>
                                        </td>
                                        <td>
                                            @if ($status === 'Active')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-point-filled me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    {{ $status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($patient->TreatmentJoined === 'Yes')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-check me-1"></i>Joined
                                                </span>
                                            @else
                                                <span class="badge bg-light text-secondary border px-2.5 py-1 fs-11 fw-medium rounded-pill">
                                                    Not Joined
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <a href="{{ route('patient.show', $patient->PatientID) }}"
                                                    class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="View Patient Details">
                                                    <i class="ti ti-eye fs-15 text-primary"></i>
                                                </a>
                                                @hasPermission('edit', 'patient')
                                                    <a href="{{ route('patient.edit', $patient->PatientID) }}"
                                                        class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Patient">
                                                        <i class="ti ti-edit fs-15 text-warning"></i>
                                                    </a>
                                                @endhasPermission
                                                @hasPermission('delete', 'patient')
                                                    <form method="POST" action="{{ route('patient.destroy', $patient->PatientID) }}" class="d-inline"
                                                        onsubmit="return confirmPatientDelete(event, this)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Delete Patient">
                                                            <i class="ti ti-trash fs-15 text-danger"></i>
                                                        </button>
                                                    </form>
                                                @endhasPermission
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-user-x fs-36 text-muted mb-2 d-block"></i>
                                            No patients found matching criteria.
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
                        <select id="perPage" class="form-select form-select-sm d-inline-block border ms-1 fw-bold text-dark" style="width:75px;"
                            onchange="window.location.href='{{ route('patient.index') }}?per_page='+this.value">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $patients->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $patients->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $patients->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$patients" :append="['per_page' => request('per_page', 10), 'search' => $filters['search'] ?? '', 'status' => $filters['status'] ?? '', 'treatment_joined' => $filters['treatment_joined'] ?? '', 'city' => $filters['city'] ?? '']" />
                </div>
            </div>

        </div>
    </div>

    <script>
        function confirmPatientDelete(event, form) {
            event.preventDefault();
            if (typeof ERPAlert !== 'undefined') {
                ERPAlert.confirm({
                    title: 'Delete Patient?',
                    text: 'Are you sure you want to delete this patient record? This action cannot be undone.',
                    icon: 'warning',
                    confirmButtonText: 'Yes, Delete',
                    onConfirm: () => form.submit()
                });
            } else if (confirm('Are you sure you want to delete this patient?')) {
                form.submit();
            }
            return false;
        }
    </script>
@endsection
