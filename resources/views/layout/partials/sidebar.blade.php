@if (
    !Route::is([
        'doctor-dashboard',
        'doctors-appointments',
        'doctors-appointment-details',
        'doctors-patient-details',
        'online-consultations',
        'doctors-schedules',
        'doctors-prescriptions',
        'doctors-prescription-details',
        'doctors-leaves',
        'doctors-reviews',
        'doctors-profile-settings',
        'doctors-password-settings',
        'doctors-notification-settings',
        'doctors-notifications',
        'patient-dashboard',
        'patient-appointments',
        'patient-appointment-details',
        'patients-doctor-details',
        'patient-doctors',
        'patient-prescriptions',
        'patient-prescription-details',
        'patient-invoices',
        'patient-invoice-details',
        'patient-profile-settings',
        'patient-password-settings',
        'patient-notifications-settings',
        'patient-notifications',
    ]))
    <!-- Search Modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-transparent">
                <div class="card shadow-none mb-0">
                    <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                        <i class="ti ti-search fs-22"></i>
                        <input type="search" class="form-control border-0" placeholder="Search">
                        <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                class="ti ti-x fs-22"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        {{-- <div>
            <a href="{{ url('index') }}" class="logo logo-normal">
                <img src="{{ URL::asset('build/img/logo.svg') }}" alt="Logo">
            </a>

            <a href="{{ url('index') }}" class="logo-small">
                <img src="{{ URL::asset('build/img/logo-small.svg') }}" alt="Logo">
            </a>

            <a href="{{ url('index') }}" class="dark-logo">
                <img src="{{ URL::asset('build/img/logo-white.svg') }}" alt="Logo">
            </a>
        </div> --}}
        <div>
            <a href="{{ url('dashboard') }}" class="logo logo-normal">
                <img src="{{ URL::asset('build/img/logo.png') }}" alt="Logo">
            </a>

            <a href="{{ url('dashboard') }}" class="logo-small">
                <img src="{{ URL::asset('build/img/logo-small.png') }}" alt="Logo">
            </a>

            <a href="{{ url('dashboard') }}" class="dark-logo">
                <img src="{{ URL::asset('build/img/logo-white.png') }}" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
            <i class="ti ti-arrow-left"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            @if (
                !Route::is([
                    'doctor-dashboard',
                    'doctors-appointments',
                    'doctors-appointment-details',
                    'doctors-patient-details',
                    'online-consultations',
                    'doctors-schedules',
                    'doctors-prescriptions',
                    'doctors-prescription-details',
                    'doctors-leaves',
                    'doctors-reviews',
                    'doctors-profile-settings',
                    'doctors-password-settings',
                    'doctors-notification-settings',
                    'doctors-notifications',
                    'patient-dashboard',
                    'patient-appointments',
                    'patient-appointment-details',
                    'patients-doctor-details',
                    'patient-doctors',
                    'patient-prescriptions',
                    'patient-prescription-details',
                    'patient-invoices',
                    'patient-invoice-details',
                    'patient-profile-settings',
                    'patient-password-settings',
                    'patient-notifications-settings',
                    'patient-notifications',
                ]))
                <div class="sidebar-top shadow-sm p-2 rounded-1 mb-3 dropend">
                    <a href="javascript:void(0);" class="drop-arrow-none" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="avatar rounded-circle flex-shrink-0 p-2"><img
                                        src="{{ URL::asset('./build/img/icons/trustcare.svg') }}" alt="img"></span>
                                <div class="ms-2">
                                    <h6 class="fs-14 fw-semibold mb-0">Dashbaord</h6>
                                    <p class="fs-13 mb-0">Branch</p>
                                </div>
                            </div>
                            <i class="ti ti-arrows-transfer-up"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg">
                        <div class="p-2">
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ URL::asset('build/img/icons/clinic-01.svg') }}"
                                            alt=""></span>
                                    <span class="fw-semibold text-dark">Coorperate<small
                                            class="d-block text-muted fw-normal fs-13">Ohio</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ URL::asset('build/img/icons/clinic-02.svg') }}"
                                            alt=""></span>
                                    <span class="fw-semibold text-dark">Trustcare Clinic<small
                                            class="d-block text-muted fw-normal fs-13">Lasvegas</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ URL::asset('build/img/icons/clinic-03.svg') }}"
                                            alt=""></span>
                                    <span class="fw-semibold text-dark">NovaCare Medical<small
                                            class="d-block text-muted fw-normal fs-13">Washington</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ URL::asset('build/img/icons/clinic-04.svg') }}"
                                            alt=""></span>
                                    <span class="fw-semibold text-dark">Greeny Medical Clinic<small
                                            class="d-block text-muted fw-normal fs-13">Illinios</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                        </div>
                    </div>
                </div>

                <ul>
                    {{-- @isAdmin
                        <li class="menu-title"><span>Staff</span></li>
                        <li>
                            <ul>
                                <li class="{{ Request::is('staff', 'staff') ? 'active' : '' }}">
                                    <a href="{{ route('staff.index') }}">
                                        <i class="ti ti-users-group"></i><span>Staff</span>
                                    </a>

                                </li>
                            </ul>
                        </li>
                    @endisAdmin --}}


                    <!-- Sidebar modules loaded from cache (1 hour TTL) -->
                    @forelse ($moduleGroups as $parentName => $modules)
                        @php
                            $moduleNames = $modules->pluck('name')->toArray();
                            $hasAnyMasterPermission =
                                session('is_admin') ||
                                \App\Helpers\RbacHelper::hasPermissionForAny('read', $moduleNames);
                        @endphp
                        @if ($hasAnyMasterPermission)
                            {{-- @if ($hasPermission) --}}
                            <li class="menu-title">
                                <span>{{ $parentName }}</span>
                            </li>
                            <li>
                                <ul>
                                    <li class="submenu">
                                        <a href="javascript:void(0);">
                                            <i class="ti ti-folder"></i>
                                            <span>{{ $parentName }}</span>
                                            <span class="menu-arrow"></span>
                                        </a>

                                        <ul>
                                            @foreach ($modules as $module)

                                                @if (session('is_admin') || \App\Helpers\RbacHelper::hasPermission('read', $module->name))
                                                    @php
                                                        $href = '#';
                                                        if ($module->route_prefix) {
                                                            try {
                                                                // Try route with .index first
                                                                $href = route($module->route_prefix . '.index');
                                                                //   dump('Using route: ' . $module->route_prefix . '.index');
                                                            } catch (\Exception $e1) {
                                                                try {
                                                                    // If that fails, try just the route name
                                                                    $href = route($module->route_prefix);
                                                                    //  dump('Using route: ' . $module->route_prefix);
                                                                } catch (\Exception $e2) {
                                                                    //   dump('Second route failed: ' . $e2->getMessage());
                                                                    $href = '#';
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    <li>
                                                        <a href="{{ $href }}">
                                                            <i class="{{ $module->icon ?? 'ti ti-app' }}"></i>
                                                            <span>{{ $module->description ?? ucfirst($module->name) }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @empty
                        <li class="text-muted text-center py-2">No modules available</li>
                    @endforelse


                    {{-- <li class="menu-title"><span>Ticket Masters</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ Request::is('category', 'branch', 'department', 'designation', 'issues-master', 'location', 'country', 'zone', 'state', 'city', 'new-branch', 'language-settings2', 'language-settings3', 'maintenance-mode-settings', 'login-and-register-settings', 'preferences-settings') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-world-cog"></i><span>Issue Configuration</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ route('category.index') }}"
                                            class="{{ Request::is('category') ? 'active' : '' }}">Issue
                                            Categories</a></li>
                                    <li><a href="{{ route('issues-master.index') }}"
                                            class="{{ Request::is('issues-master') ? 'active' : '' }}">Issue Types
                                        </a></li>
                                    <li><a href="{{ route('machine.index') }}"
                                            class="{{ Request::is('machine') ? 'active' : '' }}">Machine
                                            Machine
                                        </a></li>
                                    <li><a href="{{ route('machine-issues.index') }}"
                                            class="{{ Request::is('machine-issues') ? 'active' : '' }}">
                                            Machine Issues</a></li>


                                    <li><a href="{{ route('expanse.index') }}"
                                            class="{{ Request::is('expanse') ? 'active' : '' }}">Expense
                                        </a></li>

                                    <li><a href="{{ route('facility.issue.category.index') }}"
                                            class="{{ Request::is('facility-issue-category') ? 'active' : '' }}">Facility
                                            Issues
                                        </a></li>

                                </ul>
                            </li>
                        </ul>
                    </li> --}}


                    <li class="menu-title"><span>Support</span></li>
                    <li>
                        <ul>


                            <li class="{{ Request::is('tickets', 'ticket-details') ? 'active' : '' }}">
                                <a href="{{ route('tickets') }}">
                                    <i class="ti ti-ticket"></i><span>Tickets</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('ticket-summary*') ? 'active' : '' }}">
                                <a href="{{ route('ticket.summary') }}">
                                    <i class="ti ti-chart-bar"></i><span>Ticket Summary</span>
                                </a>
                            </li>

                        </ul>

                    <li class="menu-title"><span>Authentication</span></li>
                    @isAdmin
                        <li>
                            <ul>
                                <li class="submenu">
                                    <a href="javascript:void(0);"
                                        class="{{ Request::is('rbac*') ? 'active subdrop' : '' }}">
                                        <i class="ti ti-shield-lock"></i><span>Access Control</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="{{ route('rbac.assign-role.form') }}"
                                                class="{{ Request::is('rbac/assign-role') ? 'active' : '' }}">Assign Role
                                            </a></li>
                                        <li><a href="{{ route('rbac.assign-dept.form') }}"
                                                class="{{ Request::is('rbac/assign-department') ? 'active' : '' }}">Assign
                                                Department
                                            </a></li>
                                        <li><a href="{{ route('rbac.assign-hier.form') }}"
                                                class="{{ Request::is('rbac/assign-hierarchy') ? 'active' : '' }}">Assign
                                                Hierarchy
                                            </a></li>
                                        <li><a href="{{ route('rbac.manage-roles') }}"
                                                class="{{ Request::is('rbac/manage-roles') ? 'active' : '' }}">Manage
                                                Roles & Permissions
                                            </a></li>
                                        {{-- <li><a href="{{ route('rbac.manage-permissions-admin') }}"
                                                class="{{ Request::is('rbac/manage-permissions-admin') ? 'active' : '' }}">Manage
                                                Permissions (Admin)
                                            </a></li> --}}
                                        <li><a href="{{ route('modules.index') }}"
                                                class="{{ Request::is('rbac/modules') ? 'active' : '' }}">Manage Modules
                                            </a></li>
                                        <li><a href="{{ route('rbac.permission-guide') }}"
                                                class="{{ Request::is('rbac/permission-guide') ? 'active' : '' }}">Permission
                                                Guide
                                            </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endisAdmin
                    <li>
                        <ul>

                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-lock-exclamation"></i><span>Forgot Password</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('forgot-password-cover') }}">Cover</a></li>

                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-restore"></i><span>Reset Password</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('reset-password-cover') }}">Cover</a></li>
                                    {{-- <li><a href="{{ url('reset-password-illustration') }}">Illustration</a></li>
                                    <li><a href="{{ url('reset-password-basic') }}">Basic</a></li> --}}
                                </ul>
                            </li>

                        </ul>
                    </li>
                    <li class="menu-title"><span>Settings</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ Request::is('profile-settings', 'security-settings', 'notifications-settings', 'integrations-settings') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-user-cog"></i><span>Account Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('profile-settings') }}"
                                            class="{{ Request::is('profile-settings') ? 'active' : '' }}">Profile</a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>

                </ul>
            @endif

            @if (Route::is([
                    'doctor-dashboard',
                    'doctors-appointments',
                    'doctors-appointment-details',
                    'doctors-patient-details',
                    'online-consultations',
                    'doctors-schedules',
                    'doctors-prescriptions',
                    'doctors-prescription-details',
                    'doctors-leaves',
                    'doctors-reviews',
                    'doctors-profile-settings',
                    'doctors-password-settings',
                    'doctors-notification-settings',
                    'doctors-notifications',
                ]))
                <ul>
                    <li class="menu-title"><span>Main Menu</span></li>
                    <li>
                        <ul>
                            <li
                                class="{{ Request::is('doctor-dashboard', 'doctors-notifications') ? 'active' : '' }}">
                                <a href="{{ url('doctor-dashboard') }}">
                                    <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ Request::is('doctors-appointments', 'doctors-appointment-details', 'doctors-patient-details', 'online-consultations') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('doctors-appointments') }}"
                                            class="{{ Request::is('doctors-appointments', 'doctors-appointment-details', 'doctors-patient-details') ? 'active' : '' }}">Appointments</a>
                                    </li>
                                    <li><a href="{{ url('online-consultations') }}"
                                            class="{{ Request::is('online-consultations') ? 'active' : '' }}">Online
                                            Consultations</a></li>
                                </ul>
                            </li>
                            <li class="{{ Request::is('doctors-schedules') ? 'active' : '' }}">
                                <a href="{{ url('doctors-schedules') }}">
                                    <i class="ti ti-clock-check"></i><span>My Schedule</span>
                                </a>
                            </li>
                            <li
                                class="{{ Request::is('doctors-prescriptions', 'doctors-prescription-details') ? 'active' : '' }}">
                                <a href="{{ url('doctors-prescriptions') }}">
                                    <i class="ti ti-prescription"></i><span>Prescriptions</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('doctors-leaves') ? 'active' : '' }}">
                                <a href="{{ url('doctors-leaves') }}">
                                    <i class="ti ti-calendar-x"></i><span>Leave</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('doctors-reviews') ? 'active' : '' }}">
                                <a href="{{ url('doctors-reviews') }}">
                                    <i class="ti ti-star"></i><span>Reviews</span>
                                </a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ Request::is('doctors-profile-settings', 'doctors-password-settings', 'doctors-notification-settings') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-settings"></i><span>Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('doctors-profile-settings') }}"
                                            class="{{ Request::is('doctors-profile-settings') ? 'active' : '' }}">Profile
                                            Settings</a></li>
                                    <li><a href="{{ url('doctors-password-settings') }}"
                                            class="{{ Request::is('doctors-password-settings') ? 'active' : '' }}">Change
                                            Password</a></li>
                                    <li><a href="{{ url('doctors-notification-settings') }}"
                                            class="{{ Request::is('doctors-notification-settings') ? 'active' : '' }}">Notifications</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif

            @if (Route::is([
                    'patient-dashboard',
                    'patient-appointments',
                    'patient-appointment-details',
                    'patients-doctor-details',
                    'patient-doctors',
                    'patient-prescriptions',
                    'patient-prescription-details',
                    'patient-invoices',
                    'patient-invoice-details',
                    'patient-profile-settings',
                    'patient-password-settings',
                    'patient-notifications-settings',
                    'patient-notifications',
                ]))
                <ul>
                    <li class="menu-title"><span>Main Menu</span></li>
                    <li>
                        <ul>
                            <li
                                class="{{ Request::is('patient-dashboard', 'patient-notifications') ? 'active' : '' }}">
                                <a href="{{ url('patient-dashboard') }}">
                                    <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li
                                class="{{ Request::is('patient-appointments', 'patient-appointment-details') ? 'active' : '' }}">
                                <a href="{{ url('patient-appointments') }}">
                                    <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                </a>
                            </li>
                            <li
                                class="{{ Request::is('patient-doctors', 'patients-doctor-details') ? 'active' : '' }}">
                                <a href="{{ url('patient-doctors') }}">
                                    <i class="ti ti-stethoscope"></i><span>Doctors</span>
                                </a>
                            </li>
                            <li
                                class="{{ Request::is('patient-prescriptions', 'patient-prescription-details') ? 'active' : '' }}">
                                <a href="{{ url('patient-prescriptions') }}">
                                    <i class="ti ti-prescription"></i><span>Prescriptions</span>
                                </a>
                            </li>
                            <li
                                class="{{ Request::is('patient-invoices', 'patient-invoice-details') ? 'active' : '' }}">
                                <a href="{{ url('patient-invoices') }}">
                                    <i class="ti ti-star"></i><span>Invoice</span>
                                </a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ Request::is('patient-profile-settings', 'patient-password-settings', 'patient-notifications-settings') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-settings"></i><span>Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('patient-profile-settings') }}"
                                            class="{{ Request::is('patient-profile-settings') ? 'active' : '' }}">Profile
                                            Settings</a></li>
                                    <li><a href="{{ url('patient-password-settings') }}"
                                            class="{{ Request::is('patient-password-settings') ? 'active' : '' }}">Change
                                            Password</a></li>
                                    <li><a href="{{ url('patient-notifications-settings') }}"
                                            class="{{ Request::is('patient-notifications-settings') ? 'active' : '' }}">Notifications</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
        {{-- <div class="sidebar-footer border-top mt-3">
            <div class="trial-item mt-0 p-3 text-center">
                <div class="trial-item-icon rounded-4 mb-3 p-2 text-center shadow-sm d-inline-flex">
                    <img src="{{ URL::asset('./build/img/icons/sidebar-icon.svg') }}" alt="img">
                </div>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1">Upgrade To Pro</h6>
                    <p class="fs-13 mb-0">Check 1 min video and begin use Preclinic like a pro</p>
                </div>
                <a href="javascript:void(0);" class="close-icon shadow-sm"><i class="ti ti-x"></i></a>
            </div>
        </div> --}}
    </div>

</div>
<!-- Sidenav Menu End -->
