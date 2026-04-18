<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 me-2">Vsupport Complaint</h4>
                    <span class="badge badge-soft-primary border pt-1 px-2 border-primary fw-medium">Total Complaint :
                        582</span>
                </div>
                <div class="text-end">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_tickets"><i class="ti ti-plus me-1"></i>Raise Ticket</a>
                </div>
                @isset($assignList)
                    @component('components.modal-popup', ['assignList' => $assignList])
                    @endcomponent
                @endisset
            </div>
            <div class="container">
                <div class="row justify-content-center mt-5">

                    <div class="col-md-6 position-relative">

                        <!-- SEARCH BOX -->
                        <div class="d-flex gap-2">
                            <input type="text" id="customer_search" class="form-control"
                                placeholder="Search complaints...">

                            <button type="button" id="search_btn" class="btn btn-primary">
                                Search
                            </button>
                        </div>

                        <!-- RESULT DROPDOWN -->
                        <div id="result_area" class="bg-white shadow rounded mt-2 position-absolute w-100"
                            style="display:none; z-index:999;">

                            <table class="table table-hover table-sm mb-0">
                                <tbody id="customer_table"></tbody>
                            </table>

                        </div>

                        <!-- NO DATA -->
                        <div id="no_data" class="text-danger mt-2" style="display:none;">
                            No data found
                        </div>

                    </div>

                </div>
            </div>
            <!-- End Page Header -->

            <div class=" d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set mb-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown mb-3 pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);"
                            class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter me-2 fs-14"></i>Filter
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown" id="filter-dropdown">
                            <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                <h4 class="fs-18 fw-bold">Filter</h4>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline me-3">Clear
                                        All</a>
                                </div>
                            </div>
                            <form action="#">
                                <div class="filter-body pb-1">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                                Select
                                            </a>
                                            <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                <div class="mb-3">
                                                    <div class="input-icon-start position-relative">
                                                        <span class="input-icon-addon fs-12">
                                                            <i class="isax isax-search-normal"></i>
                                                        </span>
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                                <ul class="mb-3">
                                                    <li class="d-flex align-items-center justify-content-between mb-3">
                                                        <label class="d-inline-flex align-items-center text-gray-9">
                                                            <input class="form-check-input select-all m-0 me-2"
                                                                type="checkbox"> Select All
                                                        </label>
                                                        <a href="javascript:void(0);"
                                                            class="link-danger fw-medium text-decoration-underline">Reset</a>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-33.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Alberto Ripley
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-12.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Bernard Griffith
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-02.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Carol Lam
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-08.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Ezra Belcher
                                                        </label>
                                                    </li>
                                                </ul>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-primary w-100">Select</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Date</label>
                                        </div>
                                        <div class="input-group position-relative">
                                            <input type="text"
                                                class="form-control date-range bookingrange rounded-end h-auto py-2 bg-white">
                                            <span class="input-icon-addon fs-16 text-gray-9">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="javascript:void(0);" class="btn btn-light btn-md me-2"
                                        id="close-filter">Close</a>
                                    <button type="submit" class="btn btn-primary btn-md">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                            data-bs-toggle="dropdown">
                            <span class="me-1"> Sort By : </span> Recent
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recent</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap table-sm datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Created Date</th>
                            <th>Location</th>
                            <th>Customer Code</th>
                            <th>Customer Name</th>
                            <th>Customer Mobile</th>
                            <th>Alternate Mobile</th>
                            <th>Created By</th>
                            <th>call Assigned To</th>
                            <th>Source</th>
                            <th>Complaint</th>
                            <th>Type of Escalation</th>
                            <th>call Status</th>
                            <th>Feedback</th>
                            <th>Followups</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $item)
                            <tr>
                                <td>
                                    {{ $item->CreatedDate ? \Carbon\Carbon::parse($item->CreatedDate)->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td>
                                    {{ optional($item->Customer->location ?? null)->LocationName ?? '-' }}
                                </td>


                                <td>{{ $item->Customer->RegistrationNo }}</td>
                                <td>{{ $item->CustomerName }}</td>

                                <td>
                                    {{ $item->Customer->Mobile ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->alternateMobile ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->createdUser->FullName ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->acceptedUser->FullName ?? '-' }}
                                </td>
                                <td>{{ $item->sources }}</td>

                                <td>{{ $item->Complaint }}</td>
                                <td>{{ $item->TypeofEscalation }}</td>

                                <td>
                                    {{-- <span class="badge bg-info"> --}}
                                    {{ $item->callStatus ?? 'Open' }}
                                    {{-- </span> --}}
                                </td>
                                <td>
                                    <a href="{{ url('/complaint/view/' . $item->ReferenceNo) }}"
                                        class="btn btn-sm btn-secondary">
                                        View
                                    </a>

                                </td>
                                <td>
                                    <a href="{{ route('complaints.followup', $item->ReferenceNo) }}"
                                        class="btn btn-sm btn-primary">
                                        Follow-up
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No complaints found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        @component('components.footer')
        @endcomponent

    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#search_btn').on('click', function() {

                console.log("Button clicked");

                let search = $('#customer_search').val();

                if (search.length < 2) {
                    $('#result_area').hide();
                    $('#no_data').show();
                    return;
                }

                $.ajax({
                    url: "{{ url('/search-customer') }}",
                    type: "GET",
                    data: {
                        search: search
                    },

                    beforeSend: function() {
                        $('#customer_table').html(`
                    <tr><td colspan="7" class="text-center">Loading...</td></tr>
                `);
                    },

                    success: function(res) {

                        let rows = '';
                        let data = res.data ?? res;

                        if (data.length > 0) {

                            data.forEach(function(item, index) {

                                rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.Location ?? '-'}</td>
                                <td>${item.RegistrationNo ?? '-'}</td>
                                <td>${item.RegistrationDate ?? '-'}</td>
                                <td>${item.PatientName ?? '-'}</td>
                                <td>${item.Mobile ?? '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        onclick="addFeedback('${item.RegistrationNo}')">
                                        Add Feedback
                                    </button>
                                </td>
                            </tr>
                        `;
                            });

                            $('#customer_table').html(rows);
                            $('#result_area').show();
                            $('#no_data').hide();

                        } else {
                            $('#result_area').hide();
                            $('#no_data').show();
                        }
                    },

                    error: function() {
                        console.log("AJAX ERROR");
                    }
                });

            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "/departments",
                type: "GET",
                success: function(res) {
                    if (res.status) {
                        let options = '<option value="">Select Department</option>';
                        res.data.forEach(function(d) {
                            options +=
                                `<option value="${d.Departmentid}">${d.DepartmentName}</option>`;
                        });
                        $("#department").html(options);
                    }
                }
            });
            //  Show only if V Support (ID = 33)
            $("#department").on("change", function() {
                let deptId = $(this).val();

                if (deptId == 33) {
                    $("#vsupport_block").show();
                } else {
                    $("#vsupport_block").hide();

                    // reset values
                    $("#assign_to").val("");
                    $("#source").val("");
                    $("#call_status").val("");
                }
            });

            $("#department").change(function() {
                let deptId = $(this).val();

                $("#category").html('<option value="">Loading...</option>');
                $("#issue").html('<option value="">Select Issue</option>');

                if (deptId != "") {
                    $.ajax({
                        url: "/issue-categories",
                        type: "GET",
                        data: {
                            department_id: deptId
                        },
                        success: function(res) {
                            let options = '<option value="">Select Category</option>';
                            res.data.forEach(function(c) {
                                options +=
                                    `<option value="${c.category_id}">${c.category_name}</option>`;
                            });
                            $("#category").html(options);
                        }
                    });
                }
            });
            $("#category").change(function() {
                let categoryId = $(this).val();

                $("#issue").html('<option value="">Loading...</option>');

                if (categoryId != "") {
                    $.ajax({
                        url: "/issues/" + categoryId,
                        type: "GET",
                        success: function(res) {
                            let options = '<option value="">Select Issue</option>';
                            res.data.forEach(function(i) {
                                options +=
                                    `<option value="${i.IssueId}">${i.IssueName}</option>`;
                            });
                            $("#issue").html(options);
                        }
                    });
                }
            });

            $('#ticketForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $(form).find('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing...');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ url('tickets') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },

                    success: function(response) {
                        Swal.fire({
                            // position: "top-end",
                            icon: "success", // fixed (was type)
                            title: "Ticket Created Successfully",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = "{{ route('tickets') }}";
                        }, 1500);
                    },

                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Add Ticket');

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

                            // Scroll to first error
                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);

                        } else {

                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong!",
                            });

                            console.log(xhr.responseText);
                        }
                    }
                });
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {

            $('#customer_search').select2({
                placeholder: "Type Name / Mobile / Reg No",
                minimumInputLength: 2,
                width: '100%',

                ajax: {
                    url: "{{ url('/search-customer') }}",
                    dataType: 'json',
                    delay: 250,

                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },

                    processResults: function(response) {
                        return {
                            results: response.data.map(item => ({
                                id: item.RegistrationNo,
                                text: `${item.PatientName} (${item.RegistrationNo}) - ${item.Mobile}`
                            }))
                        };
                    },

                    cache: true
                }
            });

        });
    </script> --}}
@endsection
