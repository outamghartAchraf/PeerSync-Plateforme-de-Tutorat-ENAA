<?php

declare(strict_types=1);

namespace Src\Entities;

use InvalidArgumentException;

class Review
{


    private int $rating;

    private string $comment;

    private int $helpRequestId;

    public function __construct(

        int $rating,
        string $comment,
        int $helpRequestId
    ) {

        $this->setRating($rating);

        $this->comment = $comment;

        $this->helpRequestId = $helpRequestId;
    }



    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException(
                'Rating must be between 1 and 5'
            );
        }

        $this->rating = $rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getHelpRequestId(): int
    {
        return $this->helpRequestId;
    }

    public function setHelpRequestId(int $helpRequestId): void
    {
        $this->helpRequestId = $helpRequestId;
    }
}