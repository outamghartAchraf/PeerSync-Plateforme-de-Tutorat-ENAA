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
}