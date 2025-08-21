<?php
namespace App\Models\Notification;

class EmailNotification implements NotificationInterface {
    public function send(string $message): void {
        error_log("Email sent: $message");
    }
}