<?php
namespace App\Models\Notification;

class SMSNotification implements NotificationInterface {
    public function send(string $message): void {
        error_log("SMS sent: $message");
    }
}