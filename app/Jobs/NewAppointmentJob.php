<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\NewAppointmentEvent;

class NewAppointmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $appointment;
    public function __construct(Appointment $appointment)
    {
        $this->appointment =$appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new NewAppointmentEvent($this->appointment))->toOthers();
    }
}
