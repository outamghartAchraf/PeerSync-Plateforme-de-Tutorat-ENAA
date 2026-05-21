<?php

namespace Src\Repositories;
require_once __DIR__ . "/../../config/DB.php";
use PDO;

class HelpRequestRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo =  \DB::connect();
    }

    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) FROM help_request";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) FROM help_request WHERE status = :status";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'status' => $status
        ]);

        return (int) $stmt->fetchColumn();
    }
}