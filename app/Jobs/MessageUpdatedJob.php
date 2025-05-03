<?php

namespace App\Jobs;

use App\Events\MessageSendedEvent;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $message;
    public function __construct(Message $message)
    {
        $this->message=$message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new MessageSendedEvent($this->message));
    }
}
