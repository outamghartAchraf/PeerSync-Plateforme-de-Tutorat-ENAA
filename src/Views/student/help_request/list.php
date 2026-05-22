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

$filters = [
    'status'     => $_GET['status'] ?? '',
    'technology' => $_GET['technology'] ?? '',
];

$requests = $service->getRequests($filters);

if (!is_array($requests)) {
    $requests = [];
}

$status     = (string) $filters['status'];
$technology = (string) $filters['technology'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Requests – PeerSync</title>
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
    <div class="w-full md:w-64 md:fixed">
        <?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>
    </div>

    <!-- MAIN CONTENT -->
    <main class="ml-64 w-full p-8">

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mb-6 rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300 shadow-lg backdrop-blur">
                <?= htmlspecialchars((string) $_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <!-- PAGE HEADER -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold">Help Requests</h1>
                <p class="text-gray-400 mt-2">Browse and manage all requests</p>
            </div>
            <a href="create.php"
               class="flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-105 transition px-5 py-3 rounded-2xl font-semibold shadow-lg">
                <i class="fa-solid fa-plus"></i>
                New Request
            </a>
        </div>

        <!-- FILTERS -->
        <form method="GET"
              class="mb-8 grid gap-3 bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-4 sm:grid-cols-[180px_1fr_auto]">

            <select name="status"
                    class="rounded-2xl border border-white/10 bg-[#020617] px-4 py-3 text-sm outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20">
                <option value="">All Status</option>
                <option value="pending"  <?= strtolower($status) === 'pending'  ? 'selected' : '' ?>>PENDING</option>
                <option value="assigned" <?= strtolower($status) === 'assigned' ? 'selected' : '' ?>>ASSIGNED</option>
                <option value="resolved" <?= strtolower($status) === 'resolved' ? 'selected' : '' ?>>RESOLVED</option>
            </select>

            <input type="text"
                   name="technology"
                   placeholder="Technology..."
                   value="<?= htmlspecialchars($technology) ?>"
                   class="rounded-2xl border border-white/10 bg-[#020617] px-4 py-3 text-sm outline-none transition placeholder:text-gray-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20">

            <button class="rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-5 py-3 font-semibold transition hover:scale-105">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>Filter
            </button>

        </form>

        <!-- REQUEST LIST -->
        <div class="grid gap-5">

            <?php if (empty($requests)): ?>
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 backdrop-blur-xl p-10 text-center text-gray-400">
                    <i class="fa-solid fa-inbox text-4xl mb-3 block text-gray-600"></i>
                    No requests found.
                </div>
            <?php endif; ?>

            <?php foreach ($requests as $r): ?>

                <?php
                $requestTitle       = (string) ($r->title       ?? 'Untitled request');
                $requestDescription = (string) ($r->description ?? '');
                $requestTechnology  = (string) ($r->technology  ?? 'Unknown');
                $requestStatus      = strtoupper((string) ($r->status ?? 'PENDING'));

                $statusClass = match($requestStatus) {
                    'RESOLVED' => 'bg-green-500/20 text-green-300',
                    'ASSIGNED' => 'bg-cyan-500/20 text-cyan-300',
                    default    => 'bg-orange-500/20 text-orange-300',
                };
                $statusIcon = match($requestStatus) {
                    'RESOLVED' => 'fa-solid fa-check',
                    'ASSIGNED' => 'fa-solid fa-user-check',
                    default    => 'fa-solid fa-clock',
                };
                ?>

                <a href="details.php?id=<?= (int) ($r->id ?? 0) ?>"
                   class="group bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 transition hover:scale-[1.01] hover:bg-white/10 hover:border-cyan-400/30 block">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                        <div class="space-y-2">
                            <h2 class="text-xl font-semibold text-white transition group-hover:text-cyan-300">
                                <?= htmlspecialchars($requestTitle) ?>
                            </h2>

                            <p class="max-w-3xl text-sm leading-6 text-gray-400">
                                <?= htmlspecialchars(mb_strimwidth($requestDescription, 0, 180, '...')) ?>
                            </p>

                            <div class="flex flex-wrap gap-2 pt-1 text-sm">
                                <span class="rounded-full bg-cyan-500/20 text-cyan-300 px-3 py-1">
                                    <i class="fa-solid fa-code mr-1"></i>
                                    <?= htmlspecialchars($requestTechnology) ?>
                                </span>

                                <span class="rounded-full px-3 py-1 flex items-center gap-2 <?= $statusClass ?>">
                                    <i class="<?= $statusIcon ?>"></i>
                                    <?= htmlspecialchars($requestStatus) ?>
                                </span>
                            </div>
                        </div>

                        <div class="text-sm text-gray-400 sm:text-right shrink-0">
                            <p><i class="fa-solid fa-user mr-1"></i><?= htmlspecialchars((string) ($r->creator_name ?? 'Unknown')) ?></p>
                            <p class="mt-1"><i class="fa-regular fa-calendar mr-1"></i><?= htmlspecialchars((string) ($r->created_at ?? '')) ?></p>
                        </div>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    </main>

</div>

</body>
</html>