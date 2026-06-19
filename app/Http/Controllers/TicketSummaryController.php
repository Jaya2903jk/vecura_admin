<?php

namespace App\Http\Controllers;

use App\Models\BiomedicalTicket;
use App\Models\FacilityTicket;
use App\Models\HrTicketDetail;
use App\Models\IssueTicket;
use App\Models\PcBillSubmission;
use App\Models\PcRequest;
use Illuminate\Http\Request;

class TicketSummaryController extends Controller
{
    // Status labels used in the view
    private $statusLabels = ['Pending', 'InProgress', 'Resolved', 'Closed'];

    // Department display names
    private $deptLabels = [
        'vsupport'   => 'V-Support',
        'hr'         => 'HR',
        'biomedical' => 'Biomedical',
        'accounts'   => 'Accounts',
        'Settlement' => 'Settlement',
        'petty cash' => 'Petty Cash',
        'petty bill' => 'Petty Bill',
        'facility'   => 'Facility',
    ];

    public function index()
    {
        $totalTickets = IssueTicket::whereIn('type', ['vsupport', 'hr', 'biomedical', 'accounts', 'Settlement', 'petty cash', 'petty bill', 'facility'])->count();
        $byType = [];
        foreach ($this->deptLabels as $key => $label) {
            $counts = $this->countsByType($key);
            $counts['label'] = $label;
            $counts['key']   = $key;
            $byType[$key]    = $counts;
        }
        // Overall totals — sum across all types
        $overall = [
            'Pending'    => 0,
            'InProgress' => 1,
            'Resolved'   => 2,
            'Closed'     => 3,
            'Rejected'     => 4,
        ];
        foreach ($byType as $c) {
            foreach ($this->statusLabels as $s) {
                $overall[$s] += $c[$s];
            }
        }
        $overall['Total'] = array_sum($overall);
        return view('ticket.summary', compact('overall', 'byType'));
    }

    // ── Per-type queries ──────────────────────────────────────────────────

    private function countsByType(string $type): array
    {
        return match ($type) {
            'biomedical' => $this->stringStatusCounts(new BiomedicalTicket, 'status'),
            'facility'   => $this->stringStatusCounts(new FacilityTicket,   'status'),
            'petty cash' => $this->stringStatusCounts(new PcRequest,         'ticket_status'),
            'petty bill' => $this->stringStatusCounts(new PcBillSubmission,  'ticket_status'),
            'hr'         => $this->hrCounts(),
            default      => $this->issueTicketCounts($type),
        };
    }

    // Tables with string status: 'Pending' / 'In Progress' / 'Resolved' / 'Closed'
    private function stringStatusCounts($model, string $col): array
    {
        $rows = $model->newQuery()
            ->selectRaw("$col as st, COUNT(*) as cnt")
            ->groupBy($col)
            ->pluck('cnt', 'st')
            ->toArray();

        return [
            'Pending'    => $rows['Pending']     ?? 0,
            'InProgress' => $rows['In Progress'] ?? 0,
            'Resolved'   => $rows['Resolved']    ?? 0,
            'Closed'     => $rows['Closed']      ?? 0,
            'Total'      => array_sum($rows),
        ];
    }

    // HR uses hr_ticket_details (sqlsrv) with string status
    private function hrCounts(): array
    {
        $rows = HrTicketDetail::selectRaw('status as st, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'st')
            ->toArray();

        return [
            'Pending'    => $rows['Pending']     ?? 0,
            'InProgress' => $rows['In Progress'] ?? 0,
            'Resolved'   => $rows['Resolved']    ?? 0,
            'Closed'     => $rows['Closed']      ?? 0,
            'Total'      => array_sum($rows),
        ];
    }

    // vsupport / accounts / Settlement use IssueTicket (sqlsrv) with int Status
    // 0=Pending 1=InProgress 2=Resolved 3=Closed
    private function issueTicketCounts(string $type): array
    {
        $rows = IssueTicket::where('type', $type)
            ->selectRaw('Status as st, COUNT(*) as cnt')
            ->groupBy('Status')
            ->pluck('cnt', 'st')
            ->toArray();

        return [
            'Pending'    => $rows[0] ?? 0,
            'InProgress' => $rows[1] ?? 0,
            'Resolved'   => $rows[2] ?? 0,
            'Closed'     => $rows[3] ?? 0,
            'Total'      => IssueTicket::where('type', $type)->count(),
        ];
    }

    // ── Listing page ──────────────────────────────────────────────────────

    public function listing(Request $request)
    {
        $type        = $request->get('type');
        $statusLabel = $request->get('status_label'); // 'Pending','InProgress','Resolved','Closed'
        $perPage     = $request->get('per_page', 15);

        $typeLabel = $type ? ($this->deptLabels[$type] ?? $type) : 'All';

        $q = IssueTicket::with(['department', 'customer', 'location'])
            ->whereIn('type', array_keys($this->deptLabels))
            ->orderBy('CreatedDate', 'desc');

        if ($type) {
            $q->where('type', $type);
        }
        if ($statusLabel) {
            $intMap = ['Pending' => 0, 'InProgress' => 1, 'Resolved' => 2, 'Closed' => 3];
            if (isset($intMap[$statusLabel])) {
                $q->where('Status', $intMap[$statusLabel]);
            }
        }

        $tickets  = $q->paginate($perPage)->appends($request->all());
        $types    = $this->deptLabels;

        return view('ticket.summary_listing', compact(
            'tickets',
            'perPage',
            'statusLabel',
            'typeLabel',
            'type',
            'types'
        ));
    }
}
