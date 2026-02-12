<?php

namespace App\Events;

use App\Models\RequestMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestMessageCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public RequestMessage $message)
    {
    }

    public function broadcastOn(): array
    {
        return [new PresenceChannel('presence-request.'.$this->message->service_request_id)];
    }

    public function broadcastAs(): string
    {
        return 'request.message.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender?->name,
            'created_at' => $this->message->created_at?->toIso8601String(),
        ];
    }
}
