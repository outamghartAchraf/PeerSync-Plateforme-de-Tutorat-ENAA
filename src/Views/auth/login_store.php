<?php
declare(strict_types=1);

require_once __DIR__ . "/../../Services/AuthService.php";

use Src\Services\AuthService;

session_start();

$auth = new AuthService();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=1");
        exit;
    }

    $user = $auth->login($email, $password);

    if ($user) {
        $_SESSION["user"] = $user;
        $roleName = strtolower(trim((string) ($user->role_name ?? '')));
        if ($roleName === "student") {
            header("Location: ../student/dashboard.php");
        } else {
            header("Location: ../admin/dashboard.php");
        }

        exit;

    }

    header("Location: login.php?error=1");
    exit;
}
?>