<?php

namespace Src\Repositories;

require_once __DIR__ . "/../../config/DB.php";

use PDO;
use Src\Entities\HelpRequest;  

class HelpRequestRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = \DB::connect();
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

    public function create(HelpRequest $helpRequest): bool
    {
        $sql = "INSERT INTO help_request
            (title, description, technology, status, student_id)
            VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $helpRequest->getTitle(),
            $helpRequest->getDescription(),
            $helpRequest->getTechnology(),
            $helpRequest->getStatus(),
            $helpRequest->getStudentId()
        ]);
    }
}