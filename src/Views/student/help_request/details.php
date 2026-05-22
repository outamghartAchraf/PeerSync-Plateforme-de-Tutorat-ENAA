<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../../../Repositories/ReviewRepository.php';
require_once __DIR__ . '/../../../Services/HelpRequestService.php';

use Src\Services\HelpRequestService;
use Src\Entities\HelpRequest;
use Src\Repositories\ReviewRepository;

$service          = new HelpRequestService();
$reviewRepository = new ReviewRepository();

$user = $_SESSION['user'];
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

$requestStatus    = (string) ($request->status      ?? 'PENDING');
$creatorId        = (int)    ($request->creator_id  ?? 0);
$requestIdValue   = (int)    ($request->id          ?? 0);
$helperId         = (int)    ($request->helper_id   ?? 0);

$canAssign  = $creatorId !== (int) $user->id && $requestStatus === 'PENDING';

$canResolve =
    $creatorId === (int) $user->id &&
    $requestStatus !== 'RESOLVED'  &&
    $helperId > 0;

$canReview =
    $creatorId === (int) $user->id &&
    $requestStatus === 'RESOLVED'  &&
    $helperId > 0;

$showResolveButton =
    $creatorId === (int) $user->id &&
    $requestStatus !== 'RESOLVED';

$requestTitle       = (string) ($request->title        ?? 'Untitled request');
$requestDescription = (string) ($request->description  ?? '');
$requestTechnology  = (string) ($request->technology   ?? 'Unknown');
$creatorName        = (string) ($request->creator_name ?? 'Unknown');
$reviews            = $reviewRepository->findByRequestId($requestIdValue);

$helperName = $helperId > 0
    ? (string) ($request->helper_name ?? 'Not assigned')
    : 'Not assigned';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details — PeerSync</title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #06b6d4; border-radius: 20px; }
    </style>

    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity    = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);
    </script>
</head>

<body class="bg-[#020617] text-white min-h-screen overflow-x-hidden">

<!-- BACKGROUND -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500/20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600/20 blur-3xl rounded-full"></div>
</div>

<!-- FLASH MESSAGES -->
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="flash-message fixed left-1/2 top-4 z-50 w-[calc(100%-2rem)] max-w-xl -translate-x-1/2
                rounded-2xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300
                shadow-lg backdrop-blur">
        <i class="fa-solid fa-circle-check mr-2"></i>
        <?= htmlspecialchars((string) $_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="flash-message fixed left-1/2 top-16 z-50 w-[calc(100%-2rem)] max-w-xl -translate-x-1/2
                rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300
                shadow-lg backdrop-blur">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        <?= htmlspecialchars((string) $_SESSION['flash_error']) ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="flex">

    <!-- MAIN CONTENT (no sidebar on this page) -->
    <main class="w-full p-8">

        <!-- NAVBAR -->
        <div class="flex justify-between items-center mb-8">

            <div>
                <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">
                    <i class="fa-solid fa-file-lines mr-2"></i>Help Requests
                </p>
                <h1 class="text-4xl font-bold">Request Details</h1>
                <p class="text-gray-400 mt-2">View and manage this help request</p>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- STATUS BADGE -->
                <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold
                    <?= $requestStatus === 'RESOLVED'
                        ? 'bg-green-500/20 text-green-300'
                        : ($requestStatus === 'ASSIGNED'
                            ? 'bg-cyan-500/20 text-cyan-300'
                            : 'bg-orange-500/20 text-orange-300') ?>">
                    <?php if ($requestStatus === 'RESOLVED'): ?>
                        <i class="fa-solid fa-circle-check"></i>
                    <?php elseif ($requestStatus === 'ASSIGNED'): ?>
                        <i class="fa-solid fa-user-check"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-clock"></i>
                    <?php endif; ?>
                    <?= ucfirst(strtolower(htmlspecialchars($requestStatus))) ?>
                </span>

                <!-- NOTIFICATION -->
                <button class="bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-xl">
                    <i class="fa-regular fa-bell"></i>
                </button>

                <!-- USER AVATAR -->
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600
                            flex items-center justify-center font-bold text-lg">
                    <?= strtoupper(substr($user->name, 0, 1)) ?>
                </div>

            </div>

        </div>

        <!-- TITLE CARD -->
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-white">
                <?= htmlspecialchars($requestTitle) ?>
            </h2>
        </div>

        <!-- CONTENT GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT: Description + Activity + Reviews -->
            <div class="lg:col-span-2 space-y-6">

                <!-- DESCRIPTION -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-align-left text-cyan-400"></i>
                        Description
                    </h3>
                    <p class="text-gray-300 leading-7 whitespace-pre-line">
                        <?= htmlspecialchars($requestDescription) ?>
                    </p>
                </div>

                <!-- ACTIVITY -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-cyan-400"></i>
                        Activity
                    </h3>

                    <div class="space-y-4 text-sm">

                        <!-- TECHNOLOGY -->
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-code text-gray-500 w-4"></i>
                            <span class="text-gray-400">Technology:</span>
                            <span class="bg-cyan-500/10 border border-cyan-500/20 text-cyan-300
                                         px-3 py-0.5 rounded-full text-xs font-medium">
                                <?= htmlspecialchars($requestTechnology) ?>
                            </span>
                        </div>

                        <!-- CREATOR -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600
                                        flex items-center justify-center text-sm font-bold shrink-0">
                                <?= strtoupper(substr($creatorName, 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-semibold text-white"><?= htmlspecialchars($creatorName) ?></p>
                                <p class="text-xs text-gray-400">
                                    <i class="fa-solid fa-user mr-1"></i>Request Creator
                                </p>
                            </div>
                        </div>

                        <!-- HELPER -->
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-handshake text-gray-500 w-4"></i>
                            <span class="text-gray-400">Helper:</span>
                            <span class="text-white font-medium"><?= htmlspecialchars($helperName) ?></span>
                        </div>

                        <!-- CREATED AT -->
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-calendar text-gray-500 w-4"></i>
                            <span class="text-gray-400">Created:</span>
                            <span class="text-white font-medium">
                                <?= htmlspecialchars((string) ($request->created_at ?? '')) ?>
                            </span>
                        </div>

                    </div>
                </div>

                <!-- REVIEWS -->
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-star text-cyan-400"></i>
                        Reviews
                        <?php if (!empty($reviews)): ?>
                            <span class="ml-auto text-xs bg-cyan-500/15 border border-cyan-500/25
                                         text-cyan-300 px-3 py-1 rounded-full font-normal">
                                <?= count($reviews) ?> review<?= count($reviews) !== 1 ? 's' : '' ?>
                            </span>
                        <?php endif; ?>
                    </h3>

                    <?php if (empty($reviews)): ?>

                        <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center">
                            <i class="fa-regular fa-star text-3xl text-gray-600 mb-3 block"></i>
                            <p class="text-gray-400 text-sm">No reviews yet.</p>
                        </div>

                    <?php else: ?>

                        <div class="space-y-4">
                            <?php foreach ($reviews as $review):
                                $rating = (int) ($review->rating ?? 0);
                                $stars  = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                                if ($rating >= 4) {
                                    $badgeClass = 'bg-green-500/20 border-green-500/30 text-green-300';
                                    $starColor  = 'text-green-400';
                                } elseif ($rating === 3) {
                                    $badgeClass = 'bg-yellow-500/20 border-yellow-500/30 text-yellow-300';
                                    $starColor  = 'text-yellow-400';
                                } else {
                                    $badgeClass = 'bg-red-500/20 border-red-500/30 text-red-300';
                                    $starColor  = 'text-red-400';
                                }
                            ?>
                                <div class="bg-white/5 hover:bg-white/10 transition rounded-2xl p-5 border border-white/5">

                                    <div class="flex items-center justify-between gap-3 mb-3">

                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600
                                                        flex items-center justify-center text-xs font-bold shrink-0">
                                                <?= strtoupper(substr((string) ($review->reviewer_name ?? 'U'), 0, 1)) ?>
                                            </div>
                                            <p class="font-semibold text-white">
                                                <?= htmlspecialchars((string) ($review->reviewer_name ?? 'User')) ?>
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="<?= $starColor ?> text-base tracking-wider"><?= $stars ?></span>
                                            <span class="<?= $badgeClass ?> border px-3 py-0.5 rounded-full text-xs font-semibold">
                                                <?= $rating ?>/5
                                            </span>
                                        </div>

                                    </div>

                                    <?php if (!empty($review->comment)): ?>
                                        <p class="text-gray-300 text-sm leading-6 whitespace-pre-line
                                                   border-l-2 border-cyan-500/30 pl-4">
                                            <?= htmlspecialchars((string) $review->comment) ?>
                                        </p>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php endif; ?>
                </div>

            </div>

            <!-- RIGHT: Actions -->
            <div class="space-y-4 h-fit">

                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 space-y-4">

                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-cyan-400"></i>
                        Actions
                    </h3>

                    <!-- TAKE REQUEST -->
                    <?php if ($canAssign): ?>
                        <a href="assign.php?id=<?= $requestIdValue ?>"
                           class="flex items-center justify-center gap-3 bg-gradient-to-r from-cyan-500 to-blue-600
                                  hover:scale-105 transition py-4 rounded-2xl font-semibold shadow-lg w-full">
                            <i class="fa-solid fa-rocket"></i>
                            Take This Request
                        </a>
                    <?php endif; ?>

                    <!-- GOOGLE MEET -->
<?php if (!empty($request->getMeetLink())): ?>

    <a href="<?= htmlspecialchars($request->getMeetLink()) ?>"
       target="_blank"
       class="flex items-center justify-center gap-3 bg-cyan-500 hover:bg-cyan-400
              transition py-4 rounded-2xl font-semibold text-slate-950 w-full">

        <i class="fa-solid fa-video"></i>

        Join Google Meet

    </a>

<?php endif; ?>

                    <!-- RESOLVE -->
                    <?php if ($showResolveButton): ?>
                        <?php if ($canResolve): ?>
                            <a href="resolve.php?id=<?= $requestIdValue ?>"
                               class="flex items-center justify-center gap-3 bg-green-500/20 hover:bg-green-500/30
                                      text-green-300 transition py-4 rounded-2xl font-semibold w-full">
                                <i class="fa-solid fa-circle-check"></i>
                                Mark as Resolved
                            </a>
                        <?php else: ?>
                            <button type="button" disabled
                                    class="flex items-center justify-center gap-3 bg-white/5 text-gray-500
                                           cursor-not-allowed py-4 rounded-2xl font-semibold w-full">
                                <i class="fa-solid fa-circle-check"></i>
                                Mark as Resolved
                            </button>
                            <p class="text-xs text-orange-300 flex items-start gap-2">
                                <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                                You can resolve this request after a helper is assigned.
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- LEAVE REVIEW -->
                    <?php if ($canReview): ?>
                        <a href="../reviews/create.php?request_id=<?= $requestIdValue ?>"
                           class="flex items-center justify-center gap-3 bg-gradient-to-r from-purple-500 to-blue-600
                                  hover:scale-105 transition py-4 rounded-2xl font-semibold shadow-lg w-full">
                            <i class="fa-solid fa-star"></i>
                            Leave Review
                        </a>
                    <?php elseif ($creatorId === (int) $user->id && $requestStatus === 'RESOLVED'): ?>
                        <div class="bg-orange-500/10 border border-orange-500/20 rounded-2xl px-4 py-3
                                    text-sm text-orange-300 flex items-start gap-2">
                            <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                            This request was resolved without an assigned helper.
                        </div>
                    <?php endif; ?>

                    <!-- BACK -->
                    <a href="list.php"
                       class="flex items-center justify-center gap-3 bg-white/10 hover:bg-white/20
                              transition py-4 rounded-2xl font-semibold w-full">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to List
                    </a>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>