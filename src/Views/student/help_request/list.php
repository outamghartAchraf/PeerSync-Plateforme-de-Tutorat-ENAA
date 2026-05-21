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

// 🔥 FIX: safe GET values
$filters = [
        'status' => $_GET['status'] ?? '',
        'technology' => $_GET['technology'] ?? '',
];

$requests = $service->getRequests($filters);

// safety
if (!is_array($requests)) {
    $requests = [];
}

$status = (string) $filters['status'];
$technology = (string) $filters['technology'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="fixed left-1/2 top-4 z-50 w-[calc(100%-2rem)] max-w-xl -translate-x-1/2 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 shadow-lg backdrop-blur">
        <?= htmlspecialchars((string) $_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

    <div class="mb-8 flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-300/70">Help Requests</p>
            <h1 class="mt-2 text-3xl font-bold">Browse and manage requests</h1>
        </div>

        <a href="create.php" class="inline-flex items-center justify-center rounded-2xl bg-cyan-400 px-5 py-3 font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:bg-cyan-300">
            + New Request
        </a>
    </div>

    <!-- 🔍 FILTERS -->
    <form method="GET" class="mb-8 grid gap-3 rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur sm:grid-cols-[180px_1fr_auto]">

        <select name="status" class="rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20">
            <option value="">All Status</option>
            <option value="pending" <?= strtolower($status) === 'pending' ? 'selected' : '' ?>>PENDING</option>
            <option value="assigned" <?= strtolower($status) === 'assigned' ? 'selected' : '' ?>>ASSIGNED</option>
            <option value="resolved" <?= strtolower($status) === 'resolved' ? 'selected' : '' ?>>RESOLVED</option>
        </select>

        <input type="text"
               name="technology"
               placeholder="Technology..."
               value="<?= htmlspecialchars($technology) ?>"
               class="rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm outline-none transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20">

        <button class="rounded-2xl bg-cyan-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">
            Filter
        </button>

    </form>

    <!-- 📋 LIST -->
    <div class="grid gap-5">

        <?php if (empty($requests)): ?>
            <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-10 text-center text-slate-400">
                No requests found.
            </div>
        <?php endif; ?>

        <?php foreach ($requests as $r): ?>

            <?php
            $requestTitle = (string) ($r->title ?? 'Untitled request');
            $requestDescription = (string) ($r->description ?? '');
            $requestTechnology = (string) ($r->technology ?? 'Unknown');
            $requestStatus = strtoupper((string) ($r->status ?? 'PENDING'));
            ?>

            <a href="details.php?id=<?= (int) ($r->id ?? 0) ?>" class="group rounded-3xl border border-white/10 bg-white/5 p-6 transition hover:-translate-y-1 hover:border-cyan-400/30 hover:bg-white/10">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold text-white transition group-hover:text-cyan-200">
                            <?= htmlspecialchars($requestTitle) ?>
                        </h2>

                        <p class="max-w-3xl text-sm leading-6 text-slate-400">
                            <?= htmlspecialchars(mb_strimwidth($requestDescription, 0, 180, '...')) ?>
                        </p>

                        <div class="flex flex-wrap gap-2 pt-1 text-sm">
                            <span class="rounded-full bg-cyan-500/15 px-3 py-1 text-cyan-200">
                                <?= htmlspecialchars($requestTechnology) ?>
                            </span>

                            <span class="rounded-full px-3 py-1 <?= $requestStatus === 'RESOLVED' ? 'bg-emerald-500/15 text-emerald-200' : ($requestStatus === 'ASSIGNED' ? 'bg-cyan-500/15 text-cyan-200' : 'bg-amber-500/15 text-amber-200') ?>">
                                <?= htmlspecialchars($requestStatus) ?>
                            </span>
                        </div>
                    </div>

                    <div class="text-sm text-slate-400 sm:text-right">
                        <p>By <?= htmlspecialchars((string) ($r->creator_name ?? 'Unknown')) ?></p>
                        <p class="mt-1">Created <?= htmlspecialchars((string) ($r->created_at ?? '')) ?></p>
                    </div>

                </div>

            </a>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>