@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            {{-- Header --}}
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Ticket Summary
                        <span class="badge badge-soft-primary border border-primary page-header-badge ms-2">
                            Total : {{ $overall['Total'] }}
                        </span>
                    </h4>
                </div>
                <div>
                    <a href="{{ route('tickets') }}" class="btn btn-outline-primary btn-md fs-13">
                        <i class="ti ti-list me-1"></i> All Tickets
                    </a>
                </div>
            </div>

            {{-- ===== OVERALL STATUS CARDS ===== --}}
            @php
                $statusCodes = ['Pending' => 0, 'InProgress' => 1, 'Resolved' => 2, 'Closed' => 3];
                $cardConf = [
                    'Total' => ['bg' => 'bg-primary', 'icon' => 'ti-ticket', 'text' => 'text-white'],
                    'Pending' => ['bg' => 'bg-warning', 'icon' => 'ti-clock', 'text' => 'text-dark'],
                    'InProgress' => ['bg' => 'bg-info', 'icon' => 'ti-refresh', 'text' => 'text-white'],
                    'Resolved' => ['bg' => 'bg-success', 'icon' => 'ti-circle-check', 'text' => 'text-white'],
                    'Closed' => ['bg' => 'bg-danger', 'icon' => 'ti-circle-x', 'text' => 'text-white'],
                ];
            @endphp
            <div class="row g-3 mb-4">
                @foreach ($cardConf as $label => $conf)
                    <div class="col-xl col-md-4 col-6">
                        @if ($label === 'Total')
                            <a href="{{ route('tickets') }}" class="text-decoration-none">
                            @else
                                <a href="{{ route('tickets', ['status' => $statusCodes[$label]]) }}"
                                    class="text-decoration-none">
                        @endif
                        <div class="card border-0 {{ $conf['bg'] }} {{ $conf['text'] }} h-100">
                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                <div class="fs-28 opacity-75"><i class="ti {{ $conf['icon'] }}"></i></div>
                                <div>
                                    <div class="fs-22 fw-bold lh-1">{{ $overall[$label] }}</div>
                                    <div class="fs-12 opacity-85">{{ $label }}</div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- ===== DEPARTMENT-WISE SECTIONS ===== --}}
            @php
                $statusBadge = [
                    'Pending' => 'badge-soft-warning border border-warning text-dark',
                    'InProgress' => 'badge-soft-info border border-info',
                    'Resolved' => 'badge-soft-success border border-success',
                    'Closed' => 'badge-soft-danger border border-danger',
                ];
                $deptIcon = [
                    'vsupport' => 'ti-headset',
                    'hr' => 'ti-users',
                    'biomedical' => 'ti-stethoscope',
                    'accounts' => 'ti-cash',
                    'Settlement' => 'ti-receipt',
                ];
            @endphp

            <div class="row g-3">
                @foreach ($byType as $key => $data)
                    <div class="col-xl-6 col-12">
                        <div class="card border-0 h-100">
                            {{-- Section Heading — all primary --}}
                            <div
                                class="card-header border-bottom d-flex align-items-center gap-2 py-2 bg-primary bg-opacity-10">
                                <span class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle">
                                    <i class="ti {{ $deptIcon[$key] }} fs-16"></i>
                                </span>
                                <h6 class="mb-0 fw-bold text-primary">{{ $data['label'] }}</h6>
                                <a href="{{ route('tickets', ['type' => $key]) }}"
                                    class="badge bg-primary ms-auto text-decoration-none">
                                    {{ $data['Total'] }} Total
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Status</th>
                                                <th class="text-center">Count</th>
                                                <th class="text-center">View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($statusCodes as $sLabel => $sCode)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="badge {{ $statusBadge[$sLabel] }}">{{ $sLabel }}</span>
                                                    </td>
                                                    <td class="text-center fw-semibold">
                                                        {{ $data[$sLabel] }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($data[$sLabel] > 0)
                                                            <a href="{{ route('tickets', ['type' => $key, 'status' => $sCode]) }}"
                                                                class="btn btn-xs btn-outline-primary py-0 px-2 fs-12">
                                                                View
                                                            </a>
                                                        @else
                                                            <span class="text-muted fs-12">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="table-light fw-semibold">
                                                <td class="text-primary">Total</td>
                                                <td class="text-primary text-center">{{ $data['Total'] }}</td>
                                                <td class="text-center">
                                                    @if ($data['Total'] > 0)
                                                        <a href="{{ route('tickets', ['type' => $key]) }}"
                                                            class="btn btn-xs btn-primary py-0 px-2 fs-12 text-white">
                                                            All
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="footer text-center bg-white p-2 border-top mt-3">
            <p class="text-dark mb-0">Copyright &copy; 2026 - Vecura.</p>
        </div>
    </div>
@endsection
