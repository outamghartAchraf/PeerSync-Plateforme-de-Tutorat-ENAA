<?php

namespace Src\Services;

require_once __DIR__ . '/../Repositories/HelpRequestRepository.php';

use Src\Repositories\HelpRequestRepository;

class HelpRequestService
{
    private HelpRequestRepository $repo;

    public function __construct()
    {
        $this->repo = new HelpRequestRepository();
    }

    public function countAll(): int
    {
        return $this->repo->countAll();
    }

    public function countPending(): int
    {
        return $this->repo->countByStatus('pending');
    }

    public function countResolved(): int
    {
        return $this->repo->countByStatus('resolved');
    }


}