<?php

namespace Src\Services;

require_once __DIR__ . '/../Repositories/HelpRequestRepository.php';
include_once __DIR__ . '/../Entities/HelpRequest.php';

use Src\Repositories\HelpRequestRepository;
use Src\Entities\HelpRequest; 

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

    public function createRequest(HelpRequest $helpRequest): bool
    {
        $title = trim($helpRequest->getTitle());
        $description = trim($helpRequest->getDescription());
        $technology = trim($helpRequest->getTechnology());
        $creatorId = $helpRequest->getStudentId();

        if (empty($title) || empty($description) || empty($technology)) {
            throw new \Exception('Title, Description, and Technology are required');
        }

        $helpRequest->setTitle($title);
        $helpRequest->setDescription($description);
        $helpRequest->setTechnology($technology);
        $helpRequest->setStatus('pending');

        return $this->repo->create($helpRequest);
    }

    public function getRequests(array $filters): array
    {
        return $this->repo->getRequests($filters);
    }

        public function getRequestById(int $id): ?HelpRequest
    {
        return $this->repo->findById($id);
    }

    public function assignRequest($requestId, $userId, $creatorId)
{
     
    if ($userId == $creatorId) {
       
         throw new \Exception("You cannot assign your own request");
    }

   
    $request = $this->repo->findById($requestId);

    
    if (!$request) {
         throw new \Exception("Request not found");
    }

     
    if ($request->status != 'PENDING') {
        throw new \Exception("Request already assigned");
    }

    
    $assigned = $this->repo->assignRequest($requestId, $userId);

   
    if (!$assigned) {
        throw new \Exception("Assign failed");
    }

    return true;
}
}