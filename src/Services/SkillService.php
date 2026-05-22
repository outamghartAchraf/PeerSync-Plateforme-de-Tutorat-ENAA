<?php

namespace Src\Services;

require_once __DIR__ . '/../Repositories/SkillRepository.php';

use Src\Repositories\SkillRepository;

class SkillService
{
    private SkillRepository $repository;

    public function __construct()
    {
        $this->repository = new SkillRepository();
    }

    public function getAllSkills(): array
    {
        return $this->repository->getAllSkills();
    }

    public function getMasteredSkills(int $userId): array
    {
        return $this->repository->getUserSkills(
            $userId,
            'mastered'
        );
    }
}
