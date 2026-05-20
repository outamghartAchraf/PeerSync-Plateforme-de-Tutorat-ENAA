<?php

declare(strict_types=1);

namespace Src\Entities;

class UserSkill
{
    private int $userId;
    private int $skillId;

    public function __construct(int $userId, int $skillId) {
        $this->userId = $userId;
        $this->skillId = $skillId;

    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getSkillId(): int
    {
        return $this->skillId;
    }

    public function setSkillId(int $skillId): void
    {
        $this->skillId = $skillId;
    }


}