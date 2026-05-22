<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

 
require_once __DIR__ . '/../../../Repositories/ReviewRepository.php';

use Src\Repositories\ReviewRepository;

$repo = new ReviewRepository();
$reviews = $repo->findAll();

$user = $_SESSION['user'];
if (is_array($user)) {
    $user = (object) $user;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews — PeerSync</title>

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
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-300/70">Reviews</p>
                <h1 class="text-4xl font-bold mt-1">All Reviews</h1>
                <p class="text-gray-400 mt-2">Browse all helper ratings and feedback</p>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- BACK BUTTON -->
                <a href="../help_request/list.php"
                   class="bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Requests
                </a>

                <!-- NOTIFICATION -->
                <button class="bg-white/10 hover:bg-white/20 transition px-4 py-2 rounded-xl">
                    <i class="fa-regular fa-bell"></i>
                </button>

                <!-- USER AVATAR -->
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-lg">
                    <?= strtoupper(substr($user->name, 0, 1)) ?>
                </div>

            </div>

        </div>

        <!-- STATS ROW -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- TOTAL REVIEWS -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Total Reviews</p>
                        <h2 class="text-4xl font-bold mt-2 text-cyan-400"><?= count($reviews) ?></h2>
                    </div>
                    <i class="fa-solid fa-star text-3xl text-cyan-400"></i>
                </div>
            </div>

            <!-- AVERAGE RATING -->
            <?php
                $avg = count($reviews)
                    ? round(array_sum(array_map(fn($r) => (int)($r->rating ?? 0), $reviews)) / count($reviews), 1)
                    : 0;
            ?>
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Average Rating</p>
                        <h2 class="text-4xl font-bold mt-2 text-yellow-400"><?= $avg ?> <span class="text-xl text-gray-500">/ 5</span></h2>
                    </div>
                    <i class="fa-solid fa-trophy text-3xl text-yellow-400"></i>
                </div>
            </div>

            <!-- 5-STAR REVIEWS -->
            <?php
                $fiveStars = count(array_filter($reviews, fn($r) => (int)($r->rating ?? 0) === 5));
            ?>
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">5-Star Reviews</p>
                        <h2 class="text-4xl font-bold mt-2 text-green-400"><?= $fiveStars ?></h2>
                    </div>
                    <i class="fa-solid fa-circle-check text-3xl text-green-400"></i>
                </div>
            </div>

        </div>

        <!-- REVIEWS LIST -->
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-xl">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Reviews</h2>
                <span class="text-sm text-gray-400"><?= count($reviews) ?> total</span>
            </div>

            <?php if (empty($reviews)): ?>

                <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-12 text-center">
                    <i class="fa-regular fa-star text-4xl text-gray-600 mb-4 block"></i>
                    <p class="text-gray-400 text-lg">No reviews found.</p>
                    <p class="text-gray-600 text-sm mt-1">Reviews will appear here once helpers are rated.</p>
                </div>

            <?php else: ?>

                <div class="space-y-4">
                    <?php foreach ($reviews as $r):
                        $rating = (int)($r->rating ?? 0);
                        $stars  = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);

                        if ($rating >= 4) {
                            $ratingColor = 'text-green-400';
                            $ratingBg    = 'bg-green-500/20 border-green-500/30';
                        } elseif ($rating === 3) {
                            $ratingColor = 'text-yellow-400';
                            $ratingBg    = 'bg-yellow-500/20 border-yellow-500/30';
                        } else {
                            $ratingColor = 'text-red-400';
                            $ratingBg    = 'bg-red-500/20 border-red-500/30';
                        }
                    ?>

                        <div class="bg-white/5 hover:bg-white/10 transition rounded-2xl p-5 border border-white/5">

                            <div class="flex items-start justify-between gap-4">

                                <!-- LEFT: avatar + info -->
                                <div class="flex items-center gap-4">

                                    <!-- AVATAR -->
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        <?= strtoupper(substr((string)($r->reviewer_name ?? 'U'), 0, 1)) ?>
                                    </div>

                                    <div>
                                        <h3 class="font-semibold text-white">
                                            <?= htmlspecialchars((string)($r->reviewer_name ?? 'User')) ?>
                                        </h3>
                                        <p class="text-sm text-gray-400 mt-0.5 flex items-center gap-2">
                                            <i class="fa-solid fa-file-lines text-xs"></i>
                                            <?= htmlspecialchars((string)($r->request_title ?? '(no title)')) ?>
                                        </p>
                                        <p class="text-xs text-gray-600 mt-0.5 flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            <?= htmlspecialchars((string)($r->created_at ?? '')) ?>
                                        </p>
                                    </div>

                                </div>

                                <!-- RIGHT: rating badge -->
                                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                    <span class="<?= $ratingBg ?> border <?= $ratingColor ?> px-3 py-1 rounded-full text-sm font-semibold">
                                        <?= $rating ?>/5
                                    </span>
                                    <span class="<?= $ratingColor ?> text-lg tracking-wider">
                                        <?= $stars ?>
                                    </span>
                                </div>

                            </div>

                            <!-- COMMENT -->
                            <?php if (!empty($r->comment)): ?>
                                <div class="mt-4 pl-15">
                                    <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line border-l-2 border-cyan-500/30 pl-4">
                                        <?= htmlspecialchars((string)$r->comment) ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>
</html>