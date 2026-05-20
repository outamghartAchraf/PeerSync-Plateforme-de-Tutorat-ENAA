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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>ENAA Learning Dev - Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 flex items-center justify-center px-4">

<div class="absolute inset-0 overflow-hidden">

    <div class="absolute top-0 left-0 w-72 h-72 bg-cyan-500 opacity-20 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 right-0 w-72 h-72 bg-blue-600 opacity-20 rounded-full blur-3xl"></div>

</div>

<div class="relative w-full max-w-md">

    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">

        <div class="text-center mb-8">

            <h1 class="text-5xl font-extrabold text-white tracking-wide">
                ENAA
            </h1>

            <p class="text-cyan-300 mt-2 text-lg">
                Learning Dev Platform
            </p>

            <p class="text-gray-300 text-sm mt-3">
                Welcome back to your coding journey
            </p>

        </div>

        <?php if (!empty($error)) : ?>

            <div class="bg-red-500/20 border border-red-400 text-red-200 px-4 py-3 rounded-xl mb-5 text-sm">

                <?= $error ?>

            </div>

        <?php endif; ?>

        <form method="POST" class="space-y-5" action="login_store.php">

            <div>

                <label class="block text-gray-200 mb-2 text-sm">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    class="
                            w-full
                            px-4
                            py-3
                            rounded-xl
                            bg-white/10
                            border
                            border-white/20
                            text-white
                            placeholder-gray-400
                            focus:outline-none
                            focus:ring-2
                            focus:ring-cyan-400
                            transition
                        "
                >

            </div>

            <div>

                <label class="block text-gray-200 mb-2 text-sm">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                    class="
                            w-full
                            px-4
                            py-3
                            rounded-xl
                            bg-white/10
                            border
                            border-white/20
                            text-white
                            placeholder-gray-400
                            focus:outline-none
                            focus:ring-2
                            focus:ring-cyan-400
                            transition
                        "
                >

            </div>

            <button
                type="submit"
                class="
                        w-full
                        bg-gradient-to-r
                        from-cyan-500
                        to-blue-600
                        hover:from-cyan-400
                        hover:to-blue-500
                        text-white
                        font-bold
                        py-3
                        rounded-xl
                        transition
                        duration-300
                        shadow-lg
                        hover:scale-[1.02]
                    "
            >
                Sign In
            </button>

        </form>

        <div class="mt-6 text-center">

            <p class="text-gray-300 text-sm">

                Don't have an account?

                <a
                    href="register.php"
                    class="text-cyan-400 hover:text-cyan-300 font-semibold"
                >
                    Create Account
                </a>

            </p>

        </div>

    </div>

</div>

</body>

</html>