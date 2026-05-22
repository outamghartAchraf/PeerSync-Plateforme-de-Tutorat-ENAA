<?php

namespace Src\Repositories;

use PDO;

class NotificationRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = \DB::connect();
    }

    public function create(int $userId, string $message, ?int $requestId = null): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO notifications (user_id, request_id, message)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([
            $userId,
            $requestId,
            $message
        ]);
    }

    public function getUserNotifications(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM notifications
             WHERE user_id = ?
             ORDER BY created_at DESC"
        );

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function markAsRead(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE notifications SET is_read = 1 WHERE id = ?"
        );

        $stmt->execute([$id]);
    }
}