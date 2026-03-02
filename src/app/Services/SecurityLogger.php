<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SecurityLogger
{
    public function logLoginSuccess(int $userId, string $ipAddress): void
    {
        Log::channel('security')->info('User logged in', [
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'event' => 'login_success',
        ]);
    }

    public function logLoginFailed(string $email, string $ipAddress): void
    {
        Log::channel('security')->warning('Login failed', [
            'email' => $email,
            'ip_address' => $ipAddress,
            'event' => 'login_failed',
        ]);
    }

    public function logLogout(int $userId, string $ipAddress): void
    {
        Log::channel('security')->info('User logged out', [
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'event' => 'logout',
        ]);
    }

    public function logPasswordChanged(int $userId, string $ipAddress): void
    {
        Log::channel('security')->info('Password changed', [
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'event' => 'password_changed',
        ]);
    }

    public function logUnauthorizedAccess(int $userId, string $ipAddress, string $resource): void
    {
        Log::channel('security')->warning('Unauthorized access attempt', [
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'resource' => $resource,
            'event' => 'unauthorized_access',
        ]);
    }

    public function logSuspiciousActivity(string $ipAddress, string $description, array $context = []): void
    {
        Log::channel('security')->warning('Suspicious activity', [
            'ip_address' => $ipAddress,
            'description' => $description,
            'context' => $context,
            'event' => 'suspicious_activity',
        ]);
    }
}
