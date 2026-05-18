<?php

namespace App\Services;

use App\Events\DocumentNotificationEvent;
use App\Models\Tenant\Notification;

class NotificationService
{
    public function send(
        int $userId,
        string $type,
        string $message,
        ?int $documentId = null
    ) {

        $notification = Notification::create([
            'tenant_id' => tenant_id(),
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'document_id' => $documentId,
            'read' => false,
        ]);

        // 🔥 REALTIME EVENT
        event(new DocumentNotificationEvent(
            $userId,
            $message,
            $documentId
        ));

        return $notification;
    }
}