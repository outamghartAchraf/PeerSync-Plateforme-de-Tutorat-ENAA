<?php

namespace Src\Entities;

use InvalidArgumentException;

class Review
{
    private ?int $id = null;
    private ?int $reviewerId = null;
    private int $rating;
    private string $comment;
    private int $helpRequestId;

    private ?string $reviewerName = null;
    private ?string $requestTitle = null;
    private ?string $createdAt = null;

    public function __construct(
        ?int $id,
        ?int $reviewerId,
        int $rating,
        string $comment,
        int $helpRequestId,
        ?string $reviewerName = null,
        ?string $requestTitle = null,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->reviewerId = $reviewerId;
        $this->setRating($rating);
        $this->comment = $comment;
        $this->helpRequestId = $helpRequestId;
        $this->reviewerName = $reviewerName;
        $this->requestTitle = $requestTitle;
        $this->createdAt = $createdAt;
    }

    // ⭐ FROM DB ROW
    public static function fromRow(object $row): self
    {
        return new self(
            $row->id ?? null,
            $row->reviewer_id ?? null,
            (int) ($row->rating ?? 0),
            (string) ($row->comment ?? ''),
            (int) ($row->help_request_id ?? 0),
            $row->reviewer_name ?? null,
            $row->request_title ?? null,
            $row->created_at ?? null
        );
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReviewerId(): ?int
    {
        return $this->reviewerId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getHelpRequestId(): int
    {
        return $this->helpRequestId;
    }

    public function getReviewerName(): ?string
    {
        return $this->reviewerName;
    }

    public function getRequestTitle(): ?string
    {
        return $this->requestTitle;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    // Allow legacy view code to access snake_case properties like ->reviewer_name
    public function __get(string $name)
    {
        return match ($name) {
            'id' => $this->id,
            'reviewer_id', 'reviewerId' => $this->reviewerId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'help_request_id', 'helpRequestId' => $this->helpRequestId,
            'reviewer_name' => $this->reviewerName,
            'request_title' => $this->requestTitle,
            'created_at', 'createdAt' => $this->createdAt,
            default => null,
        };
    }

    // ⭐ SETTERS
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function setRating(int $rating): void
    {
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException("Rating must be between 1 and 5");
        }

        $this->rating = $rating;
    }

    public function setHelpRequestId(int $helpRequestId): void
    {
        $this->helpRequestId = $helpRequestId;
    }
}
