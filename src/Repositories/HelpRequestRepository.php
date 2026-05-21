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

    public function getRequests(array $filters): array
    {
        $sql = 'SELECT hr.id,
                       hr.title,
                       hr.description,
                       hr.technology,
                       UPPER(hr.status) AS status,
                       hr.student_id AS creator_id,
                       hr.tutor_id AS helper_id,
                       hr.created_at,
                       hr.resolved_at,
                       creator.name AS creator_name,
                       helper.name AS helper_name
                FROM help_request hr
                JOIN users creator ON creator.id = hr.student_id
                LEFT JOIN users helper ON helper.id = hr.tutor_id
                WHERE 1 = 1';
        $params = [];

        $status = strtolower((string) ($filters['status'] ?? ''));

        if ($status !== '' && in_array($status, ['pending', 'assigned', 'resolved'], true)) {
            $sql .= ' AND hr.status = :status';
            $params['status'] = $status;
        }

        if (!empty($filters['technology'])) {
            $sql .= ' AND hr.technology LIKE :technology';
            $params['technology'] = '%' . trim((string) $filters['technology']) . '%';
        }

        $sql .= ' ORDER BY hr.created_at DESC';

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }

        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_OBJ);

        return array_map(static fn (object $row): HelpRequest => HelpRequest::fromRow($row), $rows);
    }
}