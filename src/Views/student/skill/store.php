<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/SkillService.php';

use Src\Services\SkillService;

$user = $_SESSION['user'];

if (is_array($user)) {
    $user = (object) $user;
}

$skillId = (int) $_POST['skill_id'];
$type = $_POST['type'];

$service = new SkillService();

$service->addSkill(
    $user->id,
    $skillId,
    $type
);

header("Location: list.php");
exit;