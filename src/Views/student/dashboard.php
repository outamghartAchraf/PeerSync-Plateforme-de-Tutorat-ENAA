<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../../Services/HelpRequestService.php';

use Src\Services\HelpRequestService;

require_once __DIR__ . '/../../Services/NotificationService.php';

use Src\Services\NotificationService;



$user = is_object($_SESSION['user']) ? $_SESSION['user'] : (object) $_SESSION['user'];

$notificationService = new NotificationService();

$notifications = $notificationService->getUserNotifications((int)$user->id);
$unreadCount = $notificationService->getUnreadCount((int)$user->id);

$helpService = new HelpRequestService();

$totalRequests = $helpService->countAll();
$pendingRequests = $helpService->countPending();
$resolvedRequests = $helpService->countResolved();
$recentRequests = $helpService->getRecentRequests();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>PeerSync Dashboard</title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #06b6d4;
            border-radius: 20px;
        }
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
        <!-- SIDEBAR -->
        <div class="w-full md:w-64 md:fixed">
            <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <!-- MAIN CONTENT -->
        <main class="ml-64 w-full p-8">

            <!-- NAVBAR -->
            <div class="flex justify-between items-center mb-8">

                <div>

                    <h1 class="text-4xl font-bold">
                        Welcome back
                    </h1>

                    <p class="text-gray-400 mt-2">
                        Keep learning and helping others
                    </p>

                </div>

                <!-- RIGHT -->
                <div class="flex items-center gap-4">

                    <!-- NOTIFICATION -->
<div class="relative notification-wrapper">
    <button type="button"
            class="notification-btn bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-xl">
        <i class="fa-regular fa-bell"></i>
    </button>

    <?php if ($unreadCount > 0): ?>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 rounded-full">
            <?= $unreadCount ?>
        </span>
    <?php endif; ?>

    <!-- DROPDOWN -->
    <div class="notification-dropdown hidden absolute right-0 mt-2 w-80 bg-slate-900 border border-white/10 rounded-2xl shadow-lg p-3 z-50">
        <?php if (empty($notifications)): ?>
            <p class="text-gray-400 text-sm p-3">No notifications</p>
        <?php else: ?>
            <?php foreach ($notifications as $n): ?>
                <div class="p-2 border-b border-white/10">
                    <p class="text-sm"><?= htmlspecialchars($n->message) ?></p>
                    <p class="text-xs text-gray-500"><?= $n->created_at ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

                    <!-- USER AVATAR -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-lg">

                        <?= strtoupper(substr($user->name, 0, 1)) ?>

                    </div>

                </div>

            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                <!-- POINTS -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-400">
                                Points
                            </p>

                            <h2 class="text-4xl font-bold mt-2 text-cyan-400">

                                <?= $user->points  ?>

                            </h2>

                        </div>

                        <i class="fa-solid fa-trophy text-3xl text-cyan-400"></i>

                    </div>

                </div>

                <!-- TOTAL -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-400">
                                Total Requests
                            </p>

                            <h2 class="text-4xl font-bold mt-2">

                                <?= $totalRequests ?>

                            </h2>

                        </div>

                        <i class="fa-solid fa-file-circle-question text-3xl text-white"></i>

                    </div>

                </div>

                <!-- PENDING -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-400">
                                Pending
                            </p>

                            <h2 class="text-4xl font-bold mt-2 text-orange-400">

                                <?= $pendingRequests ?>

                            </h2>

                        </div>

                        <i class="fa-solid fa-clock text-3xl text-orange-400"></i>

                    </div>

                </div>

                <!-- RESOLVED -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-400">
                                Resolved
                            </p>

                            <h2 class="text-4xl font-bold mt-2 text-green-400">

                                <?= $resolvedRequests ?>

                            </h2>

                        </div>

                        <i class="fa-solid fa-circle-check text-3xl text-green-400"></i>

                    </div>

                </div>

            </div>

            <!-- CONTENT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- RECENT ACTIVITY -->
                <div class="lg:col-span-2 bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-xl">

                    <div class="flex justify-between items-center mb-6">

                        <h2 class="text-2xl font-bold">

                            Recent Activity

                        </h2>

                        <button class="text-cyan-400 hover:text-cyan-300">

                            View All

                        </button>

                    </div>

                    <div class="space-y-4">

                        <!-- CARD -->
                        <?php foreach ($recentRequests as $r): ?>
                            <div class="bg-white/5 hover:bg-white/10 transition rounded-2xl p-4 border border-white/5">

                                <div class="flex justify-between">

                                    <div>

                                        <h3 class="font-semibold">

                                            <?= $r->getTitle() ?>

                                        </h3>

                                        <p class="text-sm text-gray-400 mt-1">

                                            <?= $r->getDescription() ?>

                                        </p>

                                    </div>

                                    <span class="bg-orange-500/20 text-orange-300 px-3 py-1 rounded-full text-sm h-fit flex items-center gap-2">

                                        <?php if (strtolower($r->getStatus()) === 'resolved'): ?>

                                            <i class="fa-solid fa-circle-check"></i>

                                        <?php elseif (strtolower($r->getStatus()) === 'assigned'): ?>

                                            <i class="fa-solid fa-user-check"></i>

                                        <?php else: ?>

                                            <i class="fa-solid fa-clock"></i>

                                        <?php endif; ?>

                                        <?= htmlspecialchars(strtoupper($r->getStatus())) ?>

                                    </span>

                                </div>

                            </div>
                        <?php endforeach; ?>
                        <!-- CARD -->
                        <div class="bg-white/5 hover:bg-white/10 transition rounded-2xl p-4 border border-white/5">

                            <div class="flex justify-between">

                                <div>

                                    <h3 class="font-semibold">

                                        Laravel Authentication

                                    </h3>

                                    <p class="text-sm text-gray-400 mt-1">

                                        Resolved successfully

                                    </p>

                                </div>

                                <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm h-fit flex items-center gap-2">

                                    <i class="fa-solid fa-check"></i>

                                    Resolved

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- QUICK ACTIONS -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-xl">

                    <h2 class="text-2xl font-bold mb-6">

                        Quick Actions

                    </h2>

                    <div class="space-y-4">

                        <a href="help_request/create.php"
                            class="flex items-center justify-center gap-3 bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-105 transition py-4 rounded-2xl font-semibold shadow-lg">

                            <i class="fa-solid fa-plus"></i>

                            Create Request

                        </a>

                        <a href="help_request/list.php"
                            class="flex items-center justify-center gap-3 bg-white/10 hover:bg-white/20 transition py-4 rounded-2xl">

                            <i class="fa-solid fa-eye"></i>

                            View Requests

                        </a>

                        <a href="#"
                            class="flex items-center justify-center gap-3 bg-white/10 hover:bg-white/20 transition py-4 rounded-2xl">

                            <i class="fa-solid fa-user"></i>

                            View Profile

                        </a>

                    </div>

                </div>

            </div>

        </main>

    </div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const wrapper = document.querySelector('.notification-wrapper');
    const btn = wrapper.querySelector('.notification-btn');
    const dropdown = wrapper.querySelector('.notification-dropdown');

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });

    
    document.addEventListener('click', function () {
        dropdown.classList.add('hidden');
    });

});
</script>

</body>

</html>