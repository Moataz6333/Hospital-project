<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewAppointmentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $patein;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->load('patien');
    }

   
    public function broadcastOn()
    {
        return new PrivateChannel('private-doctor.' . $this->appointment->doctor->user_id);
    }

    public function broadcastAs()
    {
        return 'new-appointment';
    }
}
