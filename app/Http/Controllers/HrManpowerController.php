<?php

namespace App\Http\Controllers;

use App\Models\HrManpowerRequest;
use App\Models\IssueTicket;

class HrManpowerController extends Controller
{
    public function view($ticketId)
    {
        // Get ticket details
        $ticket = IssueTicket::with(['department', 'location', 'customer'])
            ->where('ticketId', $ticketId)
            ->first();

        // Get manpower data
        $manpower = HrManpowerRequest::where('ticketId', $ticketId)->first();

        if (! $manpower) {
            return redirect()->back()->with('error', 'Manpower request not found');
        }
        $workflowHistory = HrManpowerRequest::with([
            'department',
            'category',
            'escalation',
        ])
            ->where('ticketId', $ticketId)
            ->latest()
            ->get();

        return view('ticket.view_manpower', compact('ticket', 'manpower', 'workflowHistory'));
    }
}
