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

    public function getRecentRequests(int $limit = 5): array
{
    $sql = "SELECT hr.id,
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
            ORDER BY hr.created_at DESC
            LIMIT $limit";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

    return array_map(function ($row) {
        return HelpRequest::fromRow($row);
    }, $rows);
}

    public function getRequests(array $filters): array
    {
        $sql = 'SELECT hr.id,
                       hr.title,
                       hr.description,
                       hr.technology,
                       hr.meet_link,
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

        public function findById(int $id): ?HelpRequest
    {
        $statement = $this->pdo->prepare(
            'SELECT hr.id,
                hr.title,
                hr.description,
                hr.technology,
                hr.meet_link,
                UPPER(hr.status) AS status,
                hr.student_id AS creator_id,
                hr.tutor_id AS helper_id,
                hr.created_at,
                hr.resolved_at,
                creator.name AS creator_name,
                creator.email AS creator_email,
                helper.name AS helper_name,
                helper.email AS helper_email
             FROM help_request hr
             JOIN users creator ON creator.id = hr.student_id
             LEFT JOIN users helper ON helper.id = hr.tutor_id
             WHERE hr.id = :id
             LIMIT 1'
        );

        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_OBJ);

        return $result ? HelpRequest::fromRow($result) : null;
    }

    public function assignRequest($requestId, $helperId, $meetLink)
{
    $sql = "UPDATE help_request 
            SET tutor_id = ?, status = ?, meet_link = ?
            WHERE id = ? 
            AND status = ?
            AND student_id != ?";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        $helperId,
        'assigned',
        $meetLink,
        $requestId,
        'pending',
        $helperId
    ]);

    return $stmt->rowCount() > 0;
}

    public function resolveRequest($requestId, $creatorId)
    {
        $sql = "UPDATE help_request
            SET status = ?, resolved_at = NOW()
            WHERE id = ?
            AND student_id = ?
            AND tutor_id IS NOT NULL
            AND status != ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'resolved',
            $requestId,
            $creatorId,
            'resolved'
        ]);

        return $stmt->rowCount() > 0;
    }
}