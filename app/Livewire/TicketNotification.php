<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class TicketNotification extends Component {
    public $notifications = [];
    public $count = 0;

    protected $listeners = [
        'refreshNotification' => 'refreshData',
        'notificationAdded' => 'onNotificationAdded'
    ];

    public function mount() {
        $this->refreshData();
    }

    public function refreshData() {
        $userId = session( 'user_id' );

        $this->count = Notification::where( 'user_id', $userId )
        ->where( 'is_read', 0 )
        ->count();

        $this->notifications = Notification::where( 'user_id', $userId )
        ->latest()
        ->take( 10 )
        ->get();
    }

    public function onNotificationAdded() {
        $this->refreshData();
        $this->dispatch( 'show-toast', message: 'New Notification 🔔' );
    }

    public function markAsRead( $id ) {
        Notification::where( 'id', $id )->update( [ 'is_read' => 1 ] );

        $this->dispatch( 'toast', 'Marked as read' );
        $this->refreshData();
    }

    public function deleteNotification( $id ) {
        Notification::where( 'id', $id )->delete();

        $this->dispatch( 'toast', 'Deleted' );
        $this->refreshData();
    }

    public function render() {
        return view( 'livewire.ticket-notification' );
    }
}
