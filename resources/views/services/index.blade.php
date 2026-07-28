@extends('layout.mainlayout')

@section('content')
    <style>
        .service-code-tag {
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
            background-color: #f8fafc;
            padding: 2px 8px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            letter-spacing: 0.3px;
        }

        .service-status-select {
            font-size: 12px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            width: auto;
            min-width: 105px;
        }

        .category-badge {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 12px;
        }
    </style>

    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-box-seam text-primary fs-24"></i>Service Master Management
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage services, treatment rates, categories, SAC numbers, and tax percentages.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'services') || \App\Helpers\RbacHelper::canPerformAction('create', 'staff'))
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#addServiceModal" onclick="openAddServiceModal()">
                            <i class="ti ti-plus me-1"></i>Add New Service
                        </button>
                    @endif
                </div>
            </div>

            <!-- FILTER BAR -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <form method="GET" action="{{ route('services.index') }}" id="serviceFilterForm">
                        <div class="row g-2 align-items-center">
                            {{-- Search Input --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Search Service</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" id="searchInput" class="form-control border-start-0 fs-13 text-dark"
                                        placeholder="Search by Service Name, Code, SAC..." value="{{ $search }}">
                                </div>
                            </div>

                            {{-- Category Filter --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Category</label>
                                <select name="category" id="categoryFilter" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('serviceFilterForm').submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Status</label>
                                <select name="status" id="statusFilter" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('serviceFilterForm').submit()">
                                    <option value="">All Status</option>
                                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="In Active" {{ $status == 'In Active' ? 'selected' : '' }}>In Active</option>
                                </select>
                            </div>

                            {{-- Clear Filters --}}
                            <div class="col-md-2 d-flex align-items-end pt-3">
                                <a href="{{ route('services.index') }}" class="btn btn-light border btn-sm w-100 fw-semibold fs-13 text-secondary">
                                    <i class="ti ti-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SERVICES TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4" style="width: 70px;"># ID</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Service Code</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Service Name</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Category</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Rate (₹)</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">GST %</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">SAC Code</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Incentive</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Surgery</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="servicesTableBody">
                                @forelse ($services as $srv)
                                    @php
                                        $srvStatus = trim($srv->Status);
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted fs-13">{{ $srv->Serviceid }}</td>
                                        <td>
                                            <span class="service-code-tag">{{ $srv->ServiceCode ?? 'SER-0000' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-13">{{ $srv->ServiceName }}</div>
                                            @if($srv->kidType)
                                                <span class="badge bg-light text-secondary border fs-11 mt-0.5">{{ $srv->kidType }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="category-badge">{{ $srv->Category ?: 'Others' }}</span>
                                        </td>
                                        <td class="fw-bold text-dark fs-13">
                                            ₹{{ number_format((float)$srv->Rate, 2) }}
                                        </td>
                                        <td class="fs-13 text-dark">
                                            <span class="badge bg-info-subtle text-info border border-info-subtle fs-12 fw-semibold">
                                                {{ number_format((float)($srv->GSTPerValueS ?? 5), 1) }}%
                                            </span>
                                        </td>
                                        <td class="fs-13 text-dark font-monospace">
                                            {{ $srv->SACNumber ?: '999722' }}
                                        </td>
                                        <td class="fs-13 text-dark fw-semibold">
                                            ₹{{ number_format((float)($srv->Incentiveamt ?? 0), 2) }}
                                        </td>
                                        <td>
                                            <span class="badge {{ trim($srv->surgery) == 'Yes' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-secondary border' }} fs-11 fw-semibold">
                                                {{ trim($srv->surgery) == 'Yes' ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm service-status-select fw-bold {{ $srvStatus == 'Active' ? 'text-success border-success-subtle bg-success-subtle' : 'text-danger border-danger-subtle bg-danger-subtle' }}"
                                                onchange="toggleServiceStatus({{ $srv->Serviceid }}, this.value, this)">
                                                <option value="Active" {{ $srvStatus == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="In Active" {{ $srvStatus == 'In Active' ? 'selected' : '' }}>In Active</option>
                                            </select>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-light border btn-sm shadow-2xs px-2 py-1 me-1"
                                                title="Edit Service" onclick="loadServiceForEdit({{ $srv->Serviceid }})">
                                                <i class="ti ti-edit fs-15 text-warning"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-box-off fs-36 text-muted mb-2 d-block"></i>
                                            No service master records found matching criteria.
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
                        <select id="perPage" class="form-select form-select-sm d-inline-block border ms-1 fw-bold text-dark" style="width:75px;" onchange="window.location.href='?per_page='+this.value">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $services->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $services->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $services->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$services" :append="['per_page' => $perPage, 'search' => $search, 'category' => $category, 'status' => $status]" />
                </div>
            </div>

        </div>
    </div>

    <!-- ======== ADD SERVICE MODAL ======== -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-16"><i class="ti ti-box-seam me-2"></i>Add New Service Master</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addServiceForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Service Code <span class="text-danger">*</span></label>
                                <input type="text" name="service_code" id="addServiceCode" class="form-control form-control-sm fs-13 font-monospace fw-bold text-primary bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Service Name <span class="text-danger">*</span></label>
                                <input type="text" name="service_name" class="form-control form-control-sm fs-13" required placeholder="e.g. Cryotherapy - Upgrade">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Category</label>
                                <input type="text" name="category" class="form-control form-control-sm fs-13" placeholder="e.g. Cryotherapy, Others">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Rate (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="rate" class="form-control form-control-sm fs-13" step="0.01" min="0" required placeholder="3813.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">GST %</label>
                                <input type="number" name="gst_per_value_s" class="form-control form-control-sm fs-13" step="0.01" value="5.0" placeholder="5.0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">SAC Number</label>
                                <input type="text" name="sac_number" class="form-control form-control-sm fs-13" value="999722" placeholder="999722">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">Incentive Amt (₹)</label>
                                <input type="number" name="incentive_amt" class="form-control form-control-sm fs-13" step="0.01" value="80.0" placeholder="80.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">Surgery</label>
                                <select name="surgery" class="form-select form-select-sm fs-13">
                                    <option value="Yes">Yes</option>
                                    <option value="No" selected>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">Kit Type</label>
                                <select name="kid_type" class="form-select form-select-sm fs-13">
                                    <option value="Kit">Kit</option>
                                    <option value="non-kit" selected>non-kit</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-select-sm fs-13 fw-bold" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="In Active">In Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fs-13 fw-bold"><i class="ti ti-check me-1"></i>Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ======== EDIT SERVICE MODAL ======== -->
    <div class="modal fade" id="editServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning text-white py-3">
                    <h5 class="modal-title fw-bold fs-16 text-dark"><i class="ti ti-edit me-2"></i>Edit Service Master</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editServiceForm">
                    @csrf
                    <input type="hidden" id="editServiceId" name="service_id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Service Code</label>
                                <input type="text" id="editServiceCode" class="form-control form-control-sm fs-13 font-monospace fw-bold text-primary bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Service Name <span class="text-danger">*</span></label>
                                <input type="text" name="service_name" id="editServiceName" class="form-control form-control-sm fs-13" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Category</label>
                                <input type="text" name="category" id="editServiceCategory" class="form-control form-control-sm fs-13">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Rate (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="rate" id="editServiceRate" class="form-control form-control-sm fs-13" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">GST %</label>
                                <input type="number" name="gst_per_value_s" id="editServiceGst" class="form-control form-control-sm fs-13" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">SAC Number</label>
                                <input type="text" name="sac_number" id="editServiceSac" class="form-control form-control-sm fs-13">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark">Incentive Amt (₹)</label>
                                <input type="number" name="incentive_amt" id="editServiceIncentive" class="form-control form-control-sm fs-13" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Surgery</label>
                                <select name="surgery" id="editServiceSurgery" class="form-select form-select-sm fs-13">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" id="editServiceStatus" class="form-select form-select-sm fs-13 fw-bold" required>
                                    <option value="Active">Active</option>
                                    <option value="In Active">In Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning fs-13 fw-bold text-dark"><i class="ti ti-check me-1"></i>Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function openAddServiceModal() {
            fetch('/services/generate-code')
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('addServiceCode').value = data.service_code;
                    }
                })
                .catch(err => console.error(err));
        }

        function toggleServiceStatus(serviceId, newStatus, selectElem) {
            fetch(`/services/${serviceId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    if (selectElem) {
                        if (newStatus === 'Active') {
                            selectElem.className = 'form-select form-select-sm service-status-select fw-bold text-success border-success-subtle bg-success-subtle';
                        } else {
                            selectElem.className = 'form-select form-select-sm service-status-select fw-bold text-danger border-danger-subtle bg-danger-subtle';
                        }
                    }
                    if (typeof ERPAlert !== 'undefined') {
                        ERPAlert.toast('Service status changed to ' + newStatus, 'success');
                    }
                } else {
                    if (typeof ERPAlert !== 'undefined') {
                        ERPAlert.error('Error', data.message);
                    }
                }
            })
            .catch(err => {
                console.error(err);
                if (typeof ERPAlert !== 'undefined') {
                    ERPAlert.error('Error', 'Failed to update status: ' + err.message);
                }
            });
        }

        function loadServiceForEdit(serviceId) {
            fetch(`/services/${serviceId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status && data.service) {
                        const s = data.service;
                        document.getElementById('editServiceId').value = s.Serviceid;
                        document.getElementById('editServiceCode').value = s.ServiceCode || '';
                        document.getElementById('editServiceName').value = s.ServiceName || '';
                        document.getElementById('editServiceCategory').value = s.Category || '';
                        document.getElementById('editServiceRate').value = s.Rate || '';
                        document.getElementById('editServiceGst').value = s.GSTPerValueS || 5.0;
                        document.getElementById('editServiceSac').value = s.SACNumber || '999722';
                        document.getElementById('editServiceIncentive').value = s.Incentiveamt || 0;
                        document.getElementById('editServiceSurgery').value = (s.surgery && s.surgery.trim() === 'Yes') ? 'Yes' : 'No';
                        document.getElementById('editServiceStatus').value = (s.Status && s.Status.trim() === 'Active') ? 'Active' : 'In Active';

                        var modal = new bootstrap.Modal(document.getElementById('editServiceModal'));
                        modal.show();
                    }
                })
                .catch(err => console.error(err));
        }

        // Add Service Submission
        document.getElementById('addServiceForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            const formData = new FormData(this);
            const jsonData = Object.fromEntries(formData);

            fetch('/services/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(jsonData)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    if (typeof ERPAlert !== 'undefined') {
                        ERPAlert.success('Success', 'Service created successfully!', () => window.location.reload());
                    } else {
                        window.location.reload();
                    }
                } else {
                    if (typeof ERPAlert !== 'undefined') {
                        ERPAlert.error('Error', data.message);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(err => {
                if (typeof ERPAlert !== 'undefined') {
                    ERPAlert.error('Error', err.message);
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Edit Service Submission
        document.getElementById('editServiceForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const serviceId = document.getElementById('editServiceId').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            const formData = new FormData(this);
            const jsonData = Object.fromEntries(formData);

            fetch(`/services/${serviceId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(jsonData)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    if (typeof ERPAlert !== 'undefined') {
                        ERPAlert.success('Success', 'Service updated successfully!', () => window.location.reload());
                    } else {
                        window.location.reload();
                    }
                } else {
                    if (typeof ERPAlert !== 'undefined') {
                        ERPAlert.error('Error', data.message);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(err => {
                if (typeof ERPAlert !== 'undefined') {
                    ERPAlert.error('Error', err.message);
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    </script>
@endsection
