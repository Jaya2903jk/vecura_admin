<div class="header-item">
    <div class="dropdown me-3">

        <button class="topbar-link btn btn-icon topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
            data-bs-offset="0,24" type="button" aria-haspopup="false" aria-expanded="false">
            <i class="ti ti-bell-check fs-16 animate-ring"></i>
            {{-- <span class="notification-badge"></span> --}}
            @if ($count > 0)
                <span class="notification-badge">
                </span>
            @endif
        </button>

        <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">

            <div class="p-2 border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                    </div>
                </div>
            </div>

            <div class="notification-body position-relative z-2 rounded-0" data-simplebar>
                {{-- @forelse($tickets as $t)
                    <div class="dropdown-item notification-item py-3 text-wrap border-bottom" id="notification-1">
                        <div class="d-flex">
                            <div class="me-2 position-relative flex-shrink-0">
                                <img src="{{ URL::asset('build/img/doctors/doctor-01.jpg') }}"
                                    class="avatar-md rounded-circle" alt="">
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium text-dark"> Dr. Notification #{{ $t->ticketId }}</p>
                                <p class="mb-1 text-wrap">
                                    updated the <span class="fw-medium text-dark">surgery</span> schedule.
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-12"><i class="ti ti-clock me-1"></i>4 min ago</span>
                                    <div class="notification-action d-flex align-items-center float-end gap-2">
                                        <a href="javascript:void(0);" class="notification-read rounded-circle bg-danger"
                                            data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Make as Read" aria-label="Make as Read"></a>
                                        <button class="btn rounded-circle p-0" data-dismissible="#notification-1">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                @empty

                    <div class="p-3 text-center text-muted">
                        No Notifications
                    </div>
                @endforelse --}}

                @forelse($notifications as $n)
                    <div class="dropdown-item notification-item py-3 border-bottom">

                        <div class="d-flex">

                            <div class="me-2">
                                <img src="{{ URL::asset('build/img/doctors/doctor-01.jpg') }}"
                                    class="avatar-md rounded-circle">
                            </div>

                            <div class="flex-grow-1">

                                <p class="mb-0 fw-medium text-dark">
                                    {{ $n->title }}
                                </p>

                                <p class="mb-1 text-wrap">
                                    {{ $n->message }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center">

                                    <span class="fs-12 text-muted">
                                        <i class="ti ti-clock"></i>
                                        {{ $n->created_at->diffForHumans() }}
                                    </span>

                                    <div class="d-flex gap-2">

                                        <button wire:click="markAsRead({{ $n->id }})"
                                            class="btn btn-sm btn-success">
                                            Read
                                        </button>

                                        <button wire:click="deleteNotification({{ $n->id }})"
                                            class="btn btn-sm btn-danger">
                                            X
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty
                    <div class="p-3 text-center text-muted">
                        No Notifications
                    </div>
                @endforelse

            </div>

            <div class="p-2 rounded-bottom border-top text-center">
                <a href="{{ url('notifications') }}" class="text-center text-decoration-underline fs-14 mb-0">
                    View All Notifications
                </a>
            </div>

        </div>
    </div>
</div>
