<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompetitionManager implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $dataType;
    public $payload;
    protected $competitionId;

    /**
     * Create a new event instance.
     *
     * @param $competitionId
     * @param $dataType
     * @param $payload
     */
    public function __construct($competitionId,$dataType,$payload)
    {
        $this->competitionId = $competitionId;
        $this->dataType = $dataType;
        $this->payload  = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('competition-manager.'.$this->competitionId);
    }
}
