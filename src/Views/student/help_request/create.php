<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/HelpRequestService.php';
require_once __DIR__ . '/../../../Entities/HelpRequest.php';

use Src\Services\HelpRequestService;
use Src\Entities\HelpRequest;

$service = new HelpRequestService();
$user = $_SESSION['user'];

$error = '';
$title = '';
$description = '';
$technology = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $technology  = trim($_POST['technology']  ?? '');

    try {

        $helpRequest = new HelpRequest(
            0,
            $title,
            $description,
            $technology,
            $user->id
        );

        $service->createRequest($helpRequest);

        $_SESSION['success'] = 'Request created successfully';

        header("Location: list.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Help Request – PeerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #06b6d4; border-radius: 20px; }
    </style>
</head>

<body class="bg-[#020617] text-white min-h-screen overflow-x-hidden">

<!-- BACKGROUND BLOBS -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600/20 blur-3xl rounded-full"></div>
</div>

<div class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64 h-screen fixed bg-white/5 backdrop-blur-2xl border-r border-white/10 p-6 z-40">

        <h1 class="text-3xl font-bold text-cyan-400 mb-10">
            <i class="fa-solid fa-graduation-cap"></i>
            PeerSync
        </h1>

        <nav class="space-y-3">

            <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/dashboard.php"
               class="flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/help_request/list.php"
               class="flex items-center gap-3 bg-white/10 text-cyan-400 px-4 py-3 rounded-2xl transition">
                <i class="fa-solid fa-list-check"></i>
                Requests
            </a>

            <a href="#"
               class="flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition">
                <i class="fa-solid fa-circle-check"></i>
                Solved
            </a>

            <a href="#"
               class="flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition">
                <i class="fa-solid fa-user"></i>
                Profile
            </a>

            <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/auth/logout.php"
               class="flex items-center gap-3 hover:bg-red-500/20 text-red-400 px-4 py-3 rounded-2xl transition">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>

        </nav>

        <div class="absolute bottom-6 left-6 right-6">
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                    <?= strtoupper(substr($user->name ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Logged in as</p>
                    <p class="font-bold text-sm"><?= htmlspecialchars($user->name ?? 'User') ?></p>
                </div>
            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="ml-64 w-full p-8 flex items-center justify-center min-h-screen">

        <div class="w-full max-w-2xl">

            <!-- PAGE HEADER -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold">New Request</h1>
                    <p class="text-gray-400 mt-2">Provide enough detail for another student to help quickly</p>
                </div>
                <a href="list.php"
                   class="flex items-center gap-2 bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-2xl text-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>

            <!-- CARD -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-8">

                <!-- ERROR -->
                <?php if ($error): ?>
                    <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- FORM -->
                <form method="POST" class="space-y-5">

                    <div class="space-y-2">
                        <label class="text-sm text-gray-400 font-medium">
                            <i class="fa-solid fa-heading mr-1"></i> Title
                        </label>
                        <input type="text"
                               name="title"
                               value="<?= htmlspecialchars($title) ?>"
                               placeholder="e.g. How to fix N+1 query in Laravel?"
                               class="w-full rounded-2xl border border-white/10 bg-[#020617] px-4 py-3 outline-none transition placeholder:text-gray-600 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20"
                               required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm text-gray-400 font-medium">
                            <i class="fa-solid fa-align-left mr-1"></i> Description
                        </label>
                        <textarea name="description"
                                  rows="6"
                                  placeholder="Describe your problem in detail..."
                                  class="w-full rounded-2xl border border-white/10 bg-[#020617] px-4 py-3 outline-none transition placeholder:text-gray-600 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 resize-none"
                                  required><?= htmlspecialchars($description) ?></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm text-gray-400 font-medium">
                            <i class="fa-solid fa-code mr-1"></i> Technology
                        </label>
                        <input type="text"
                               name="technology"
                               value="<?= htmlspecialchars($technology) ?>"
                               placeholder="PHP, React, SQL, Laravel..."
                               class="w-full rounded-2xl border border-white/10 bg-[#020617] px-4 py-3 outline-none transition placeholder:text-gray-600 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20"
                               required>
                    </div>

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-105 transition py-4 rounded-2xl font-semibold shadow-lg mt-2">
                        <i class="fa-solid fa-plus"></i>
                        Create Request
                    </button>

                </form>

            </div>

        </div>

    </main>

</div>

</body>
</html>