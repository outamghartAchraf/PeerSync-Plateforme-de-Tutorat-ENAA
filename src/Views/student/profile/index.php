<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/AuthService.php';
require_once __DIR__ . '/../../../Services/SkillService.php';
require_once __DIR__ . '/../../../Services/HelpRequestService.php';

use Src\Services\AuthService;
use Src\Services\SkillService;
use Src\Services\HelpRequestService;

$user = $_SESSION['user'];

if (is_array($user)) {
    $user = (object) $user;
}

$authService  = new AuthService();
$skillService = new SkillService();
$helpService  = new HelpRequestService();

$currentUser      = $authService->getUserById($user->id);
$masteredSkills   = $skillService->getMasteredSkills($user->id);
$learningSkills   = $skillService->getLearningSkills($user->id);
$totalRequests    = $helpService->countAll();
$pendingRequests  = $helpService->countPending();
$resolvedRequests = $helpService->countResolved();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile — PeerSync</title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #06b6d4; border-radius: 20px; }
    </style>
</head>

<body class="bg-[#020617] text-white min-h-screen overflow-x-hidden">

<!-- BACKGROUND -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600/20 blur-3xl rounded-full"></div>
</div>

<div class="flex">

    <!-- SIDEBAR -->
    <div class="w-full md:w-64 md:fixed">
        <?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>
    </div>

    <!-- MAIN CONTENT -->
    <main class="ml-64 w-full p-8">

        <!-- NAVBAR -->
        <div class="flex justify-between items-center mb-8">

            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-300/70 mb-1">
                    <i class="fa-solid fa-user mr-2"></i>Account
                </p>
                <h1 class="text-4xl font-bold">My Profile</h1>
                <p class="text-gray-400 mt-2">View and manage your account details</p>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- EDIT PROFILE -->
                <a href="edit.php"
                   class="flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600
                          hover:scale-105 transition px-5 py-3 rounded-2xl font-semibold shadow-lg">
                    <i class="fa-solid fa-pen"></i>
                    Edit Profile
                </a>

                <!-- NOTIFICATION -->
                <button class="bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-xl">
                    <i class="fa-regular fa-bell"></i>
                </button>

                <!-- USER AVATAR -->
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600
                            flex items-center justify-center font-bold text-lg">
                    <?= strtoupper(substr($currentUser->name, 0, 1)) ?>
                </div>

            </div>

        </div>

        <!-- PROFILE HEADER CARD -->
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8">

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                <!-- AVATAR -->
                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600
                            flex items-center justify-center text-4xl font-bold shrink-0">
                    <?= strtoupper(substr($currentUser->name, 0, 1)) ?>
                </div>

                <!-- INFO -->
                <div class="flex-1 text-center sm:text-left">

                    <h2 class="text-3xl font-bold">
                        <?= htmlspecialchars($currentUser->name) ?>
                    </h2>

                    <p class="text-gray-400 mt-1 flex items-center justify-center sm:justify-start gap-2">
                        <i class="fa-regular fa-envelope text-sm"></i>
                        <?= htmlspecialchars($currentUser->email) ?>
                    </p>

                    <div class="mt-4 flex flex-wrap gap-3 justify-center sm:justify-start">

                        <!-- ROLE BADGE -->
                        <span class="bg-cyan-500/20 border border-cyan-500/30 text-cyan-300
                                     px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2">
                            <i class="fa-solid fa-id-badge text-xs"></i>
                            <?= htmlspecialchars($currentUser->role ?? 'Student') ?>
                        </span>

                        <!-- POINTS BADGE -->
                        <span class="bg-amber-500/20 border border-amber-500/30 text-amber-300
                                     px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2">
                            <i class="fa-solid fa-trophy text-xs"></i>
                            <?= $currentUser->points ?? 0 ?> Points
                        </span>

                        <!-- SKILLS COUNT -->
                        <span class="bg-purple-500/20 border border-purple-500/30 text-purple-300
                                     px-4 py-1.5 rounded-full text-sm font-medium flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-xs"></i>
                            <?= count($masteredSkills) + count($learningSkills) ?> Skills
                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- TOTAL -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Total Requests</p>
                        <h2 class="text-4xl font-bold mt-2"><?= $totalRequests ?></h2>
                    </div>
                    <i class="fa-solid fa-file-circle-question text-3xl text-white/40"></i>
                </div>
            </div>

            <!-- PENDING -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Pending</p>
                        <h2 class="text-4xl font-bold mt-2 text-orange-400"><?= $pendingRequests ?></h2>
                    </div>
                    <i class="fa-solid fa-clock text-3xl text-orange-400"></i>
                </div>
            </div>

            <!-- RESOLVED -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Resolved</p>
                        <h2 class="text-4xl font-bold mt-2 text-green-400"><?= $resolvedRequests ?></h2>
                    </div>
                    <i class="fa-solid fa-circle-check text-3xl text-green-400"></i>
                </div>
            </div>

        </div>

        <!-- SKILLS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <!-- MASTERED -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-400"></i>
                        Mastered Skills
                    </h3>
                    <span class="bg-green-500/20 border border-green-500/30 text-green-300
                                 text-xs px-3 py-1 rounded-full font-semibold">
                        <?= count($masteredSkills) ?>
                    </span>
                </div>

                <?php if (empty($masteredSkills)): ?>
                    <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-6 text-center">
                        <i class="fa-solid fa-circle-check text-2xl text-gray-600 mb-2 block"></i>
                        <p class="text-gray-400 text-sm">No mastered skills yet.</p>
                        <a href="../skills/create.php"
                           class="inline-block mt-2 text-cyan-400 hover:text-cyan-300 text-sm">
                            + Add a skill
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($masteredSkills as $skill): ?>
                            <span class="bg-green-500/15 border border-green-500/25 text-green-300
                                         px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-check text-xs"></i>
                                <?= htmlspecialchars($skill->name) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- LEARNING -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-purple-400"></i>
                        Learning Skills
                    </h3>
                    <span class="bg-purple-500/20 border border-purple-500/30 text-purple-300
                                 text-xs px-3 py-1 rounded-full font-semibold">
                        <?= count($learningSkills) ?>
                    </span>
                </div>

                <?php if (empty($learningSkills)): ?>
                    <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-6 text-center">
                        <i class="fa-solid fa-book-open text-2xl text-gray-600 mb-2 block"></i>
                        <p class="text-gray-400 text-sm">No learning skills yet.</p>
                        <a href="../skills/create.php"
                           class="inline-block mt-2 text-cyan-400 hover:text-cyan-300 text-sm">
                            + Add a skill
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($learningSkills as $skill): ?>
                            <span class="bg-purple-500/15 border border-purple-500/25 text-purple-300
                                         px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-book-open text-xs"></i>
                                <?= htmlspecialchars($skill->name) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <!-- QUICK ACTIONS -->
    

    </main>

</div>

</body>
</html>