<?php
namespace App\Models\Traits;

trait LoggingTrait {
    protected function logAction(string $action): void {
        error_log("[" . date('Y-m-d H:i:s') . "] $action");
    }
}