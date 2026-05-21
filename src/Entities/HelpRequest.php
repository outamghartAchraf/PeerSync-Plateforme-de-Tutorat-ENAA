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
    private ?string $creatorName;
    private ?string $creatorEmail;
    private ?string $helperName;
    private ?string $helperEmail;

    public function __construct(
     
        string $title,
        string $description,
        string $technology,
        int $studentId,
        ?int $tutorId = null,
        string $status = 'pending',
        ?string $createdAt = null,
        ?string $resolvedAt = null,
        ?string $creatorName = null,
        ?string $creatorEmail = null,
        ?string $helperName = null,
        ?string $helperEmail = null
    ) {

        
        $this->title = $title;
        $this->description = $description;
        $this->technology = $technology;
        $this->studentId = $studentId;
        $this->tutorId = $tutorId;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->resolvedAt = $resolvedAt;
        $this->creatorName = $creatorName;
        $this->creatorEmail = $creatorEmail;
        $this->helperName = $helperName;
        $this->helperEmail = $helperEmail;
    }

    public static function fromRow(object $row): self
    {
        return new self(
            
            (string) ($row->title ?? ''),
            (string) ($row->description ?? ''),
            (string) ($row->technology ?? ''),
            isset($row->creator_id) ? (int) $row->creator_id : (isset($row->student_id) ? (int) $row->student_id : 0),
            isset($row->helper_id) ? (int) $row->helper_id : (isset($row->tutor_id) ? (int) $row->tutor_id : null),
            (string) ($row->status ?? 'pending'),
            isset($row->created_at) ? (string) $row->created_at : null,
            isset($row->resolved_at) ? (string) $row->resolved_at : null,
            isset($row->creator_name) ? (string) $row->creator_name : null,
            isset($row->creator_email) ? (string) $row->creator_email : null,
            isset($row->helper_name) ? (string) $row->helper_name : null,
            isset($row->helper_email) ? (string) $row->helper_email : null,
        );
    }

    public function __get(string $name)
    {
        return match ($name) {
           
            'title' => $this->title,
            'description' => $this->description,
            'technology' => $this->technology,
            'status' => $this->status,
            'studentId' => $this->studentId,
            'student_id' => $this->studentId,
            'creator_id' => $this->studentId,
            'tutorId' => $this->tutorId,
            'tutor_id' => $this->tutorId,
            'helper_id' => $this->tutorId,
            'createdAt' => $this->createdAt,
            'created_at' => $this->createdAt,
            'resolvedAt' => $this->resolvedAt,
            'resolved_at' => $this->resolvedAt,
            'creatorName' => $this->creatorName,
            'creator_name' => $this->creatorName,
            'creatorEmail' => $this->creatorEmail,
            'creator_email' => $this->creatorEmail,
            'helperName' => $this->helperName,
            'helper_name' => $this->helperName,
            'helperEmail' => $this->helperEmail,
            'helper_email' => $this->helperEmail,
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return in_array($name, ['id', 'title', 'description', 'technology', 'status', 'studentId', 'student_id', 'creator_id', 'tutorId', 'tutor_id', 'helper_id', 'createdAt', 'created_at', 'resolvedAt', 'resolved_at', 'creatorName', 'creator_name', 'creatorEmail', 'creator_email', 'helperName', 'helper_name', 'helperEmail', 'helper_email'], true);
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

    public function getCreatorName(): ?string
    {
        return $this->creatorName;
    }

    public function setCreatorName(?string $creatorName): void
    {
        $this->creatorName = $creatorName;
    }

    public function getCreatorEmail(): ?string
    {
        return $this->creatorEmail;
    }

    public function setCreatorEmail(?string $creatorEmail): void
    {
        $this->creatorEmail = $creatorEmail;
    }

    public function getHelperName(): ?string
    {
        return $this->helperName;
    }

    public function setHelperName(?string $helperName): void
    {
        $this->helperName = $helperName;
    }

    public function getHelperEmail(): ?string
    {
        return $this->helperEmail;
    }

    public function setHelperEmail(?string $helperEmail): void
    {
        $this->helperEmail = $helperEmail;
    }
}