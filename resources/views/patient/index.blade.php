@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            {{-- Page Header --}}
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Patients List
                        <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
                            Total Patients : {{ $patients->total() }}
                        </span>
                    </h4>
                </div>
                <div class="text-end d-flex">
                    {{-- Export dropdown (UI only — wire up controller routes when export is ready) --}}
                    <div class="dropdown me-1">
                        <a href="javascript:void(0);"
                            class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li><a class="dropdown-item" href="{{ route('patient.export', ['format' => 'pdf'] + request()->query()) }}">Download as PDF</a></li>
                            <li><a class="dropdown-item" href="{{ route('patient.export', ['format' => 'excel'] + request()->query()) }}">Download as Excel</a></li>
                        </ul>
                    </div>

                    @hasPermission('create', 'patient')
                        <a href="{{ route('patient.create') }}" class="btn btn-primary ms-2 fs-13 btn-md">
                            <i class="ti ti-plus me-1"></i>New Patient
                        </a>
                    @endhasPermission
                </div>
            </div>
            {{-- End Page Header --}}

            {{-- Success Message --}}
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>{{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Start Filter --}}
            <form method="GET" action="{{ route('patient.index') }}">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <div class="search-set mb-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="table-search d-flex align-items-center mb-0">
                                    <div class="search-input position-relative">
                                        <span class="input-icon-addon fs-14 position-absolute top-50 start-0 translate-middle-y ms-2">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control form-control-sm ps-4"
                                            placeholder="Name / Mobile / Email" value="{{ $filters['search'] ?? '' }}"
                                            style="min-width: 240px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex table-dropdown mb-3 right-content align-items-center flex-wrap row-gap-3">
                        <div class="dropdown me-2">
                            <a href="javascript:void(0);"
                                class="bg-white border rounded btn btn-md text-dark fs-14 py-1 align-items-center d-flex fw-normal"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-filter text-gray-5 me-1"></i>Filters
                            </a>
                            <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0" id="filter-dropdown">
                                <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                    <h4 class="mb-0 fw-bold">Filter</h4>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('patient.index') }}" class="link-danger text-decoration-underline">Clear All</a>
                                    </div>
                                </div>
                                <div class="filter-body pb-0">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="">All Status</option>
                                            @foreach ($filterOptions['statuses'] as $status)
                                                <option value="{{ $status }}"
                                                    {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select form-select-sm">
                                            <option value="">All Types</option>
                                            @foreach ($filterOptions['types'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ ($filters['type'] ?? '') === $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Treatment Status</label>
                                        <select name="treatment_joined" class="form-select form-select-sm">
                                            <option value="">All</option>
                                            <option value="Yes" {{ ($filters['treatment_joined'] ?? '') === 'Yes' ? 'selected' : '' }}>Joined</option>
                                            <option value="No" {{ ($filters['treatment_joined'] ?? '') === 'No' ? 'selected' : '' }}>Not Joined</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <select name="city" class="form-select form-select-sm">
                                            <option value="">All Cities</option>
                                            @foreach ($filterOptions['cities'] as $city)
                                                <option value="{{ $city }}"
                                                    {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="{{ route('patient.index') }}" class="btn btn-light btn-md me-2 fw-medium" id="close-filter">Close</a>
                                    <button type="submit" class="btn btn-primary btn-md fw-medium">Filter</button>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                                data-bs-toggle="dropdown">
                                <span class="me-1">Sort By :</span> {{ ($filters['sort'] ?? 'recent') === 'oldest' ? 'Oldest' : 'Recent' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-2">
                                <li><a href="{{ route('patient.index', array_merge(request()->query(), ['sort' => 'recent'])) }}" class="dropdown-item rounded-1">Recent</a></li>
                                <li><a href="{{ route('patient.index', array_merge(request()->query(), ['sort' => 'oldest'])) }}" class="dropdown-item rounded-1">Oldest</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
            {{-- End Filter --}}

            {{-- Start Table --}}
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Reg No.</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Treatment</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    @php
                                        $initials = strtoupper(substr($patient->FirstName ?? 'P', 0, 1) . substr($patient->LastName ?? '', 0, 1));
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('patient.show', $patient->PatientID) }}"
                                                    class="avatar avatar-md me-2 bg-primary-transparent rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                                    <span class="fw-semibold text-primary">{{ $initials }}</span>
                                                </a>
                                                <a href="{{ route('patient.show', $patient->PatientID) }}" class="text-dark fw-semibold">
                                                    {{ $patient->FirstName }} {{ $patient->LastName }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-info border border-info">
                                                {{ $patient->RegistrationNo }}
                                            </span>
                                        </td>
                                        <td>{{ $patient->Mobile }}</td>
                                        <td class="small">{{ $patient->EMail ?? '—' }}</td>
                                        <td>
                                            @if ($patient->CustomerStatus === 'Active')
                                                <span class="badge badge-soft-success rounded text-success border border-success fs-13 fw-medium">Active</span>
                                            @else
                                                <span class="badge badge-soft-warning rounded text-warning border border-warning fs-13 fw-medium">
                                                    {{ $patient->CustomerStatus }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($patient->TreatmentJoined === 'Yes')
                                                <span class="badge badge-soft-info rounded text-info border border-info fs-13 fw-medium">Joined</span>
                                            @else
                                                <span class="badge badge-soft-secondary rounded text-secondary border border-secondary fs-13 fw-medium">Not Joined</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <a href="{{ route('patient.show', $patient->PatientID) }}"
                                                    class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end p-2">
                                                    @hasPermission('edit', 'patient')
                                                        <li>
                                                            <a class="dropdown-item rounded-1"
                                                                href="{{ route('patient.edit', $patient->PatientID) }}">
                                                                <i class="ti ti-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                    @endhasPermission
                                                    @hasPermission('delete', 'patient')
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('patient.destroy', $patient->PatientID) }}"
                                                                onsubmit="return confirm('Are you sure?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item rounded-1 text-danger">
                                                                    <i class="ti ti-trash me-2"></i>Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endhasPermission
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-inbox" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No patients found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($patients->count())
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ ($patients->currentPage() - 1) * $patients->perPage() + 1 }} to
                            {{ min($patients->currentPage() * $patients->perPage(), $patients->total()) }}
                            of {{ $patients->total() }} results
                        </div>
                        {{ $patients->links() }}
                    </div>
                @endif
            </div>
            {{-- End Table --}}

        </div>
        <!-- End Content -->

        <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">{{ date('Y') }} &copy; <a href="javascript:void(0);" class="link-primary">Preclinic</a>, All Rights Reserved</p>
        </div>

    </div>

@endsection
