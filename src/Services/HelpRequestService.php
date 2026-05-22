<?php

namespace Src\Services;

require_once __DIR__ . '/../Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../Repositories/UserRepository.php';
include_once __DIR__ . '/../Entities/HelpRequest.php';
include_once __DIR__ . '/../Services/NotificationService.php';

use Src\Repositories\HelpRequestRepository;
use Src\Entities\HelpRequest;
use Src\Repositories\UserRepository;
use Src\Services\NotificationService;


class HelpRequestService
{
    private HelpRequestRepository $repo;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->repo = new HelpRequestRepository();
        $this->userRepository = new UserRepository();
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

    public function getRecentRequests(): array
    {
        return $this->repo->getRecentRequests();
    }

    public function getRequests(array $filters): array
    {
        return $this->repo->getRequests($filters);
    }

    public function getRequestById(int $id): ?HelpRequest
    {
        return $this->repo->findById($id);
    }

    public function assignRequest($requestId, $userId, $creatorId, $meetLink)
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


        $assigned = $this->repo->assignRequest($requestId, $userId, $meetLink);


        if (!$assigned) {
            throw new \Exception("Assign failed");
        }

        
        $notificationService = new NotificationService();

        $notificationService->notify(
            $creatorId,
            "Your request has been assigned. Join Meet: " . $meetLink,
            $requestId
        );

        return true;
    }

    public function resolveRequest($requestId, $userId, $creatorId)
    {

        if ($userId != $creatorId) {
            throw new \Exception("Only creator can resolve this request");
        }

        $request = $this->repo->findById($requestId);


        if (!$request) {
            throw new \Exception("Request not found");
        }


        if ($request->creator_id != $creatorId) {
            throw new \Exception("Access denied");
        }

        if (empty($request->helper_id)) {
            throw new \Exception("No helper assigned");
        }


        if ($request->status == 'RESOLVED') {
            throw new \Exception("Request already resolved");
        }


        $resolved = $this->repo->resolveRequest($requestId, $creatorId);


        if (!$resolved) {
            throw new \Exception("Resolve failed");
        }


        // Add points

        $this->userRepository->addPoints($request->helper_id, 10);
        $this->userRepository->addPoints($request->creator_id, 5);
        return true;
    }
}
