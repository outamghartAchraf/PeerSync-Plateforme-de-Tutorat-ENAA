<?php

namespace Src\Services;

require_once __DIR__ . '/../Repositories/ReviewRepository.php';
require_once __DIR__ . '/../Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../Entities/Review.php';

use Src\Repositories\ReviewRepository;
use Src\Repositories\HelpRequestRepository;
use Src\Entities\Review;

class ReviewService
{
    private ReviewRepository $repo;
    private HelpRequestRepository $helpRequestRepository;

    public function __construct()
    {
        $this->repo = new ReviewRepository();
        $this->helpRequestRepository = new HelpRequestRepository();
    }

    public function createReview(Review $review): bool
    {
        $requestId  = $review->getHelpRequestId();
        $reviewerId = $review->getReviewerId();
        $rating     = $review->getRating();
        $comment    = trim($review->getComment());


        if ($requestId <= 0 || $reviewerId <= 0) {
            throw new \Exception("Invalid data");
        }

        if ($rating < 1 || $rating > 5) {
            throw new \Exception("Rating must be between 1 and 5");
        }

        if (empty($comment)) {
            throw new \Exception("Comment is required");
        }


        $request = $this->helpRequestRepository->findById($requestId);

        if (!$request) {
            throw new \Exception("Request not found");
        }


        if (($request->status ?? null) !== 'RESOLVED') {
            throw new \Exception("Request must be resolved before reviewing");
        }


        if ((int)$request->creator_id !== $reviewerId) {
            throw new \Exception("Only creator can review");
        }


        if ($this->repo->exists($requestId, $reviewerId)) {
            throw new \Exception("Already reviewed");
        }


        $review->setComment($comment);

        return $this->repo->create($review);
    }


}