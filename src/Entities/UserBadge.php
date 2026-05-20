<?php

declare(strict_types=1);

namespace Src\Entities;

class UserBadge
{
    private int $userId;

    private int $badgeId;

    public function __construct(
        int $userId,
        int $badgeId
    ) {
        $this->userId = $userId;
        $this->badgeId = $badgeId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getBadgeId(): int
    {
        return $this->badgeId;
    }

    public function setBadgeId(int $badgeId): void
    {
        $this->badgeId = $badgeId;
    }
}