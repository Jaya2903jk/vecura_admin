<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\IssueTicket;

class TicketNotification extends Component {
    public $tickets = [];
    public $count = 0;

    protected $listeners = [
        'ticketAdded' => 'refreshData'
    ];

    public function mount() {
        $this->refreshData();
    }

    public function refreshData() {
        $this->count = IssueTicket::where( 'type', 'complaint' )
        ->where( 'Status', 0 )
        ->count();
     // dd($this->count);
        $this->tickets = IssueTicket::where( 'type', 'complaint' )
        ->where( 'Status', 0 )
        ->orderBy( 'ticketId', 'desc' )
        ->take( 10 )
        ->get();
    }

    public function markAsRead( $id ) {
        IssueTicket::where( 'ticketId', $id )->update( [
            'Status' => 0
        ] );

        $this->refreshData();
    }

    public function deleteNotification( $id ) {
        IssueTicket::where( 'ticketId', $id )->delete();
        $this->refreshData();
    }

    public function render() {
        return view( 'livewire.ticket-notification' );
    }
}
