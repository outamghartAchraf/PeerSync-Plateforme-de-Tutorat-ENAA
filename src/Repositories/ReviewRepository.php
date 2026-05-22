<?php

namespace Src\Repositories;

require_once __DIR__ . '/../../config/DB.php';
require_once __DIR__ . '/../Entities/Review.php';

use Src\Entities\Review;
use PDO;

class ReviewRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = \DB::connect();
    }

    public function create(Review $review): bool
    {
        $sql = "INSERT INTO review (help_request_id, reviewer_id, rating, comment)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $review->getHelpRequestId(),
            $review->getReviewerId(),
            $review->getRating(),
            $review->getComment()
        ]);
    }

    public function exists(int $requestId, int $reviewerId): bool
    {
        $sql = "SELECT COUNT(*) 
                FROM review
                WHERE help_request_id = ?
                AND reviewer_id = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $requestId,
            $reviewerId
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function findByRequestAndReviewer(int $requestId, int $reviewerId): ?Review
    {
        $sql = "SELECT r.*, u.name AS reviewer_name, h.title AS request_title
            FROM review r
            JOIN users u ON u.id = r.reviewer_id
            JOIN help_request h ON h.id = r.help_request_id
            WHERE r.help_request_id = ?
            AND r.reviewer_id = ?
            LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $requestId,
            $reviewerId
        ]);

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        return $row ? Review::fromRow($row) : null;
    }

    public function findByRequestId(int $requestId): array
    {
        $sql = "SELECT r.*, u.name AS reviewer_name, h.title AS request_title
            FROM review r
            JOIN users u ON u.id = r.reviewer_id
            JOIN help_request h ON h.id = r.help_request_id
            WHERE r.help_request_id = ?
            ORDER BY r.id DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$requestId]);

        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        return array_map(static fn($row) => Review::fromRow($row), $rows);
    }
}