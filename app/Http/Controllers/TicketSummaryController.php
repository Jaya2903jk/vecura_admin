<?php

namespace App\Http\Controllers;

use App\Models\IssueTicket;
use Illuminate\Http\Request;

class TicketSummaryController extends Controller
{
    private $types = [
        'vsupport'   => 'VSupport',
        'hr'         => 'HR',
        'biomedical' => 'Biomedical',
        'accounts'   => 'Accounts',
        'Settlement' => 'Settlement',
    ];

    private $statuses = [
        0 => 'Pending',
        1 => 'InProgress',
        2 => 'Resolved',
        3 => 'Closed',
    ];

    public function index()
    {
        // Overall totals
        $overall = [];
        foreach ($this->statuses as $code => $label) {
            $overall[$label] = IssueTicket::whereIn('type', array_keys($this->types))
                ->where('Status', $code)
                ->count();
        }
        $overall['Total'] = array_sum($overall);

        // Per-type counts
        $byType = [];
        foreach ($this->types as $key => $label) {
            $counts = ['label' => $label, 'key' => $key, 'Total' => 0];
            foreach ($this->statuses as $code => $statusLabel) {
                $counts[$statusLabel] = IssueTicket::where('type', $key)
                    ->where('Status', $code)
                    ->count();
            }
            $counts['Total'] = IssueTicket::where('type', $key)->count();
            $byType[$key] = $counts;
        }

        return view('ticket.summary', compact('overall', 'byType'));
    }

    public function listing(Request $request)
    {
        $type      = $request->get('type');
        $statusCode = $request->get('status');
        $perPage   = $request->get('per_page', 15);

        $statusLabel = $statusCode !== null ? ($this->statuses[$statusCode] ?? 'All') : 'All';
        $typeLabel   = $type ? ($this->types[$type] ?? $type) : 'All';

        $q = IssueTicket::with(['department', 'customer', 'location'])
            ->whereIn('type', array_keys($this->types))
            ->orderBy('CreatedDate', 'desc');

        if ($type) {
            $q->where('type', $type);
        }
        if ($statusCode !== null && $statusCode !== '') {
            $q->where('Status', $statusCode);
        }

        $tickets     = $q->paginate($perPage)->appends($request->all());
        $statuses    = $this->statuses;
        $types       = $this->types;

        return view('ticket.summary_listing', compact(
            'tickets', 'perPage', 'statusLabel', 'typeLabel',
            'type', 'statusCode', 'statuses', 'types'
        ));
    }
}
