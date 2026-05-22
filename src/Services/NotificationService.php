<?php

namespace Src\Services;

require_once __DIR__ . '/../Repositories/NotificationRepository.php';

use Src\Repositories\NotificationRepository;

class NotificationService
{
    private NotificationRepository $repo;

    public function __construct()
    {
        $this->repo = new NotificationRepository();
    }

    public function notify(int $userId, string $message, ?int $requestId = null): void
    {
        $this->repo->create($userId, $message, $requestId);
    }

    public function getUserNotifications(int $userId): array
    {
        return $this->repo->getUserNotifications($userId);
    }

    public function getUnreadCount(int $userId): int
    {
        $notifications = $this->repo->getUserNotifications($userId);

        return count(array_filter($notifications, function ($n) {
            return (int)$n->is_read === 0;
        }));
    }

    public function markAsRead(int $notificationId): void
    {
        $this->repo->markAsRead($notificationId);
    }

    public function markAllAsRead(int $userId): void
    {
        $notifications = $this->repo->getUserNotifications($userId);

        foreach ($notifications as $n) {
            if ((int)$n->is_read === 0) {
                $this->repo->markAsRead($n->id);
            }
        }
    }
}