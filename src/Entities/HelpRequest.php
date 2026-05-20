<?php

declare(strict_types=1);

namespace Src\Entities;

use Exception;

class HelpRequest
{
    private string $title;

    private string $description;

    private string $technology;

    private string $status;

    private int $studentId;

    private ?int $tutorId;

    private ?string $createdAt;

    private ?string $resolvedAt;

    public function __construct(string $title, string $description,
        string $technology,
        int $studentId,
        ?int $tutorId = null,
        string $status = 'pending',
        ?string $createdAt = null,
        ?string $resolvedAt = null
    ) {

        $this->title = $title;
        $this->description = $description;
        $this->technology = $technology;
        $this->studentId = $studentId;
        $this->tutorId = $tutorId;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->resolvedAt = $resolvedAt;
    }

    public function assignTo(int $tutorId): void
    {
        if ($this->studentId === $tutorId) {
            throw new Exception(
                'You cannot help yourself'
            );
        }

        if ($this->tutorId !== null) {
            throw new Exception(
                'Request already assigned'
            );
        }

        $this->tutorId = $tutorId;

        $this->status = 'assigned';
    }

    public function markAsResolved(): void
    {
        $this->status = 'resolved';

        $this->resolvedAt = date('Y-m-d H:i:s');
    }


    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getTechnology(): string
    {
        return $this->technology;
    }

    public function setTechnology(string $technology): void
    {
        $this->technology = $technology;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function getTutorId(): ?int
    {
        return $this->tutorId;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getResolvedAt(): ?string
    {
        return $this->resolvedAt;
    }
}