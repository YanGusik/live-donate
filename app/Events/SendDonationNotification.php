<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendDonationNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $token;
    public $nickname;
    public $message;
    public $amount;

    public function __construct(string $token, string $nickname, string $message, float $amount)
    {
        $this->token = $token;
        $this->nickname = $nickname;
        $this->message = $message;
        $this->amount = $amount;
    }

    public function broadcastOn()
    {
        return new Channel('alert.'.$this->token);
    }
}
