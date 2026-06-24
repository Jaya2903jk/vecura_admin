@extends('layout.mainlayout')
@section('content')
<style>
    .ts-page { padding: 4px 0 32px; }

    /* ── Overall stat cards ── */
    .ts-stat-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }
    .ts-stat-card {
        background: #fff;
        border: 1px solid #e7e8eb;
        border-radius: 8px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: box-shadow .15s;
    }
    .ts-stat-card:hover { box-shadow: 0 2px 10px rgba(0,0,0,.08); }
    .ts-stat-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 19px; flex-shrink: 0;
    }
    .ts-stat-val { font-size: 22px; font-weight: 800; color: #18284d; line-height: 1; }
    .ts-stat-lbl { font-size: 12px; color: #6f7790; font-weight: 600; margin-top: 2px; }

    /* ── Department cards ── */
    .ts-dept-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 14px;
    }
    .ts-dept-card {
        background: #fff;
        border: 1px solid #e7e8eb;
        border-radius: 8px;
        overflow: hidden;
    }
    .ts-dept-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid #e7e8eb;
        background: #fafbfd;
    }
    .ts-dept-icon {
        width: 34px; height: 34px; border-radius: 8px;
        background: #eef0fb;
        display: flex; align-items: center; justify-content: center;
        color: #3741b0; font-size: 16px; flex-shrink: 0;
    }
    .ts-dept-name { font-size: 14px; font-weight: 700; color: #132144; }
    .ts-dept-total {
        margin-left: auto;
        font-size: 11px; font-weight: 700;
        background: #3741b0; color: #fff;
        border-radius: 20px; padding: 2px 10px;
        text-decoration: none;
    }
    .ts-dept-total:hover { background: #2a3299; color: #fff; }

    /* ── Status rows inside dept card ── */
    .ts-status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 16px;
        border-bottom: 1px solid #f3f4f8;
        font-size: 13px;
    }
    .ts-status-row:last-child { border-bottom: none; }
    .ts-status-left { display: flex; align-items: center; gap: 8px; }
    .ts-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .ts-status-name { color: #4b5375; font-weight: 600; }
    .ts-status-count { font-size: 14px; font-weight: 800; color: #18284d; }
    .ts-view-btn {
        font-size: 11px; font-weight: 700;
        color: #3741b0; background: #eef0fb;
        border: none; border-radius: 4px;
        padding: 2px 8px; text-decoration: none;
        white-space: nowrap;
    }
    .ts-view-btn:hover { background: #d8dbf7; color: #3741b0; }

    @media (max-width: 768px) {
        .ts-stat-grid { grid-template-columns: repeat(2, 1fr); }
        .ts-dept-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrapper">
<div class="content ts-page">

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <h4 class="fw-bold mb-0">Ticket Summary</h4>
            <span class="badge badge-soft-primary border border-primary pt-1 px-2 fw-medium">
                Total : {{ $overall['Total'] }}
            </span>
        </div>
        <a href="{{ route('tickets') }}" class="btn btn-outline-primary btn-sm">
            <i class="ti ti-list me-1"></i> All Tickets
        </a>
    </div>

    @php
        $statCards = [
            'Total'      => ['icon'=>'ti-ticket',       'bg'=>'#eef0fb', 'color'=>'#3741b0', 'status'=>null],
            'Pending'    => ['icon'=>'ti-clock',        'bg'=>'#fef6e4', 'color'=>'#8f5e00', 'status'=>'Pending'],
            'InProgress' => ['icon'=>'ti-loader',       'bg'=>'#e4f3fb', 'color'=>'#125e80', 'status'=>'InProgress'],
            'Resolved'   => ['icon'=>'ti-circle-check', 'bg'=>'#e8f7ef', 'color'=>'#1a7a3c', 'status'=>'Resolved'],
            'Closed'     => ['icon'=>'ti-lock',         'bg'=>'#f1f2f5', 'color'=>'#4b5673', 'status'=>'Closed'],
        ];
        $deptMeta = [
            'vsupport'   => ['icon'=>'ti-headset',            'label'=>'V-Support'],
            'hr'         => ['icon'=>'ti-users',              'label'=>'HR'],
            'biomedical' => ['icon'=>'ti-heart-rate-monitor', 'label'=>'Biomedical'],
            'accounts'   => ['icon'=>'ti-calculator',         'label'=>'Accounts'],
            'Settlement' => ['icon'=>'ti-receipt',            'label'=>'Settlement'],
            'petty cash' => ['icon'=>'ti-cash',               'label'=>'Petty Cash'],
            'petty bill' => ['icon'=>'ti-file-invoice',       'label'=>'Petty Bill'],
            'facility'   => ['icon'=>'ti-building',           'label'=>'Facility'],
        ];
        $statusRows = [
            'Pending'    => ['dot'=>'#f2b21b', 'code'=>0],
            'InProgress' => ['dot'=>'#2f80ed', 'code'=>1],
            'Resolved'   => ['dot'=>'#2eb85c', 'code'=>2],
            'Closed'     => ['dot'=>'#9098a8', 'code'=>3],
        ];
    @endphp

    {{-- ── Overall Stat Cards ── --}}
    <div class="ts-stat-grid">
        @foreach($statCards as $label => $cfg)
        <a href="{{ $cfg['status'] !== null ? route('ticket.summary.listing', ['status_label' => $cfg['status']]) : route('ticket.summary.listing') }}"
           class="ts-stat-card">
            <div class="ts-stat-icon" style="background:{{ $cfg['bg'] }};">
                <i class="ti {{ $cfg['icon'] }}" style="color:{{ $cfg['color'] }};"></i>
            </div>
            <div>
                <div class="ts-stat-val">{{ $overall[$label] }}</div>
                <div class="ts-stat-lbl">{{ $label === 'InProgress' ? 'In Progress' : $label }}</div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- ── Department Cards ── --}}
    <div class="ts-dept-grid">
        @foreach($byType as $key => $data)
        @php $meta = $deptMeta[$key] ?? ['icon'=>'ti-ticket','label'=>$data['label']]; @endphp
        <div class="ts-dept-card">
            <div class="ts-dept-header">
                <div class="ts-dept-icon">
                    <i class="ti {{ $meta['icon'] }}"></i>
                </div>
                <div class="ts-dept-name">{{ $meta['label'] }}</div>
                <a href="{{ route('ticket.summary.listing', ['type' => $key]) }}" class="ts-dept-total">
                    {{ $data['Total'] }} Total
                </a>
            </div>
            @foreach($statusRows as $sLabel => $sCfg)
            <div class="ts-status-row">
                <div class="ts-status-left">
                    <span class="ts-dot" style="background:{{ $sCfg['dot'] }};"></span>
                    <span class="ts-status-name">{{ $sLabel === 'InProgress' ? 'In Progress' : $sLabel }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="ts-status-count">{{ $data[$sLabel] }}</span>
                    @if($data[$sLabel] > 0)
                    <a href="{{ route('ticket.summary.listing', ['type' => $key, 'status_label' => $sLabel]) }}"
                       class="ts-view-btn">View</a>
                    @else
                    <span style="font-size:11px;color:#c0c6d4;width:34px;text-align:center;">—</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    <div style="text-align:center;font-size:12px;color:#6f7790;padding:24px 0 8px;">
        Powered by Vecura &nbsp;·&nbsp; All rights reserved
    </div>

</div>
</div>
@endsection
