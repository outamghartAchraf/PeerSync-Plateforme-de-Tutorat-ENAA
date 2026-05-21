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



}