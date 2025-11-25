<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MetricUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $idColmena,
        public ?float $temperatura,
        public ?float $humedad,
        public ?float $peso,
        public string $t
    ) {}

    public function broadcastOn()
    {
        return new Channel('metrics');
    }

    public function broadcastAs()
    {
        return 'metric.updated';
    }
}


