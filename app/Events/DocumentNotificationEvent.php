<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DocumentNotificationEvent implements ShouldBroadcast
{
    public $userId;
    public $message;
    public $documentId;

    public function __construct($userId, $message, $documentId = null)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->documentId = $documentId;
    }

    public function broadcastOn()
    {
        
    }

    public function broadcastAs()
    {
        return 'document.notification';
    }
}
