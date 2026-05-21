<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}


require_once __DIR__ . '/../../../Repositories/HelpRequestRepository.php';

require_once __DIR__ . '/../../../Services/HelpRequestService.php';

use Src\Services\HelpRequestService;
use Src\Entities\HelpRequest;

$service = new HelpRequestService();
 

$user = $_SESSION['user'];

// Normalize session user to object
if (is_array($user)) {
    $user = (object) $user;
}

if (!isset($_GET['id'])) {
    die("Request ID is required");
}

$request = $service->getRequestById((int) $_GET['id']);

if (!$request) {
    die("Request not found");
}

$requestStatus = (string) ($request->status ?? 'PENDING');
$creatorId = (int) ($request->creator_id ?? 0);
$requestIdValue = (int) ($request->id ?? 0);
$helperId = (int) ($request->helper_id ?? 0);

$canAssign = $creatorId !== (int) $user->id && $requestStatus === 'PENDING';

$canResolve =
    $creatorId === (int) $user->id &&
    $requestStatus !== 'RESOLVED' &&
    $helperId > 0;

$canReview =
    $creatorId === (int) $user->id &&
    $requestStatus === 'RESOLVED' &&
    $helperId > 0;

$showResolveButton =
    $creatorId === (int) $user->id &&
    $requestStatus !== 'RESOLVED';

 

$requestTitle = (string) ($request->title ?? 'Untitled request');
$requestDescription = (string) ($request->description ?? '');
$requestTechnology = (string) ($request->technology ?? 'Unknown');
$creatorName = (string) ($request->creator_name ?? 'Unknown');

$helperName =
    $helperId > 0
        ? (string) ($request->helper_name ?? 'Not assigned')
        : 'Not assigned';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Request Details</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(el => {
                el.style.display = 'none';
            });
        }, 4000);
    </script>

</head>

<body class="min-h-screen bg-slate-950 text-white">

<?php if (!empty($_SESSION['flash_success'])): ?>

    <div class="flash-message fixed left-1/2 top-4 z-50 w-[calc(100%-2rem)] max-w-xl -translate-x-1/2 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 shadow-lg backdrop-blur">

        <?= htmlspecialchars((string) $_SESSION['flash_success']) ?>

    </div>

    <?php unset($_SESSION['flash_success']); ?>

<?php endif; ?>


<?php if (!empty($_SESSION['flash_error'])): ?>

    <div class="flash-message fixed left-1/2 top-16 z-50 w-[calc(100%-2rem)] max-w-xl -translate-x-1/2 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200 shadow-lg backdrop-blur">

        <?= htmlspecialchars((string) $_SESSION['flash_error']) ?>

    </div>

    <?php unset($_SESSION['flash_error']); ?>

<?php endif; ?>


<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-slate-950/20 backdrop-blur lg:p-8">

        <!-- HEADER -->
        <div class="flex flex-col gap-4 border-b border-white/10 pb-6 sm:flex-row sm:items-start sm:justify-between">

            <div>

                <p class="text-sm uppercase tracking-[0.3em] text-cyan-300/70">
                    Request Details
                </p>

                <h1 class="mt-2 text-3xl font-bold text-white">
                    <?= htmlspecialchars($requestTitle) ?>
                </h1>

            </div>

            <span class="inline-flex self-start rounded-full px-4 py-2 text-sm font-semibold
                <?= $requestStatus === 'RESOLVED'
                    ? 'bg-emerald-500/15 text-emerald-200'
                    : ($requestStatus === 'ASSIGNED'
                        ? 'bg-cyan-500/15 text-cyan-200'
                        : 'bg-amber-500/15 text-amber-200') ?>">

                <?= ucfirst(strtolower(htmlspecialchars($requestStatus))) ?>

            </span>

        </div>

        <!-- CONTENT -->
        <div class="grid gap-6 py-6 lg:grid-cols-[1.4fr_0.9fr]">

            <!-- LEFT -->
            <div class="space-y-6">

                <!-- DESCRIPTION -->
                <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5">

                    <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">
                        Description
                    </h2>

                    <p class="mt-4 whitespace-pre-line leading-7 text-slate-300">
                        <?= htmlspecialchars($requestDescription) ?>
                    </p>

                </div>

                <!-- ACTIVITY -->
                <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5">

                    <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">
                        Activity
                    </h2>

                    <div class="mt-4 space-y-4 text-sm text-slate-300">

                        <p>
                            <span class="text-slate-500">Technology:</span>
                            <?= htmlspecialchars($requestTechnology) ?>
                        </p>

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500 text-sm font-bold text-slate-950">
                                <?= strtoupper(substr($creatorName, 0, 1)) ?>
                            </div>

                            <div>
                                <p class="font-semibold">
                                    <?= htmlspecialchars($creatorName) ?>
                                </p>

                                <p class="text-xs text-slate-500">
                                    Request Creator
                                </p>
                            </div>

                        </div>

                        <p>
                            <span class="text-slate-500">Helper:</span>
                            <?= htmlspecialchars($helperName) ?>
                        </p>

                        <p>
                            <span class="text-slate-500">Created:</span>
                            <?= htmlspecialchars((string) ($request->created_at ?? '')) ?>
                        </p>

                    </div>

                </div>

                <!-- COMMENTS -->
                <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5">

                    <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">
                        Comments
                    </h2>

                    <div class="mt-4 space-y-4">

   

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <aside class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/50 p-5">

                <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">
                    Actions
                </h2>

                <?php if ($canAssign): ?>

                    <a href="assign.php?id=<?= $requestIdValue ?>"
                       class="inline-flex w-full items-center justify-center rounded-2xl bg-cyan-400 px-4 py-3 font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:bg-cyan-300">

                        Take This Request 🚀

                    </a>

                <?php endif; ?>

                <?php if ($showResolveButton): ?>

                    <?php if ($canResolve): ?>

                        <a href="resolve.php?id=<?= $requestIdValue ?>"
                           class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-400 px-4 py-3 font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:bg-emerald-300">

                            Mark as Resolved

                        </a>

                    <?php else: ?>

                        <button type="button"
                                disabled
                                class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-2xl bg-emerald-400/40 px-4 py-3 font-semibold text-slate-300">

                            Mark as Resolved

                        </button>

                        <p class="text-xs text-amber-200/90">
                            You can resolve this request after a helper is assigned.
                        </p>

                    <?php endif; ?>

                <?php endif; ?>

                <?php if ($canReview): ?>

                    <a href="../reviews/create.php?request_id=<?= $requestIdValue ?>"
                       class="inline-flex w-full items-center justify-center rounded-2xl bg-purple-400 px-4 py-3 font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:bg-purple-300">

                        Leave Review ⭐

                    </a>

                <?php elseif ($creatorId === (int) $user->id && $requestStatus === 'RESOLVED'): ?>

                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">

                        This request was resolved without an assigned helper.

                    </div>

                <?php endif; ?>

                <a href="list.php"
                   class="inline-flex w-full items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 font-semibold text-white transition hover:bg-white/10">

                    Back to List

                </a>

            </aside>

        </div>

    </div>

</div>

</body>
</html>