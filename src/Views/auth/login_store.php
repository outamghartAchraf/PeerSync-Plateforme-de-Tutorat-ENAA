<?php
declare(strict_types=1);

require_once __DIR__ . "/../../Services/AuthService.php";

use Src\Services\AuthService;

session_start();

$auth = new AuthService();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"] ;
    $password = $_POST["password"] ;

    $user = $auth->login($email, $password);

    if ($user) {
        $_SESSION["user"] = $user;
        $roleName = strtolower(trim((string) ($user["role_name"] ?? '')));
        if ($roleName === "student") {
            header("Location: ../student/dashboard.php");
        } else {
            header("Location: ../admin/dashboard.php");
        }

        exit;

    } else {
        $error = "Invalid email or password ";
    }
}
?>


<?php
echo password_hash("123456", PASSWORD_DEFAULT);

?>