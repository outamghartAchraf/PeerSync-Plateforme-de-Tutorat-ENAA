<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../../../Services/HelpRequestService.php';

use Src\Services\HelpRequestService;

$service = new HelpRequestService();

$user = $_SESSION['user'];


if (is_array($user)) {
    $user = (object) $user;
}



if (!isset($_GET['id'])) {
    die("Request ID required");
}

$requestId = (int) $_GET['id'];


$request = $service->getRequestById($requestId);

if (!$request) {
    die("Request not found");
}

if ((int) $request->creator_id !== (int) $user->id) {
    die('Only the creator can resolve this request');
}

try {

    $service->resolveRequest(
        $requestId,
        $user->id,
        (int) $request->creator_id
    );

    $_SESSION['flash_success'] = 'Request marked as resolved.';

    header("Location: details.php?id=" . $requestId);
    exit;

} catch (Exception $e) {
    $_SESSION['flash_error'] = $e->getMessage();

    header("Location: details.php?id=" . $requestId);
    exit;
}