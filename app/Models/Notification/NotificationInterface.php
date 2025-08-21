<?php
namespace App\Models\Notification;

interface NotificationInterface {
    public function send(string $message): void;
}