<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/AuthService.php';

use Src\Services\AuthService;

$user = $_SESSION['user'];

if (is_array($user)) {
    $user = (object) $user;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);

$service = new AuthService();

$service->updateProfile(
    $user->id,
    $name,
    $email
);

$_SESSION['user']->name = $name;
$_SESSION['user']->email = $email;

header("Location: index.php");
exit;