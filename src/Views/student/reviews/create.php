<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

 
require_once __DIR__ . '/../../../Repositories/ReviewRepository.php';
require_once __DIR__ . '/../../../Services/ReviewService.php';
require_once __DIR__ . '/../../../Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../../../Services/HelpRequestService.php';
require_once __DIR__ . '/../../../Entities/Review.php';

use Src\Entities\Review;
use Src\Services\ReviewService;
use Src\Services\HelpRequestService;

$reviewService = new ReviewService();
$requestService = new HelpRequestService();

$user = $_SESSION['user'];

if (is_array($user)) {
    $user = (object) $user;
}

if (!isset($user->id) || $user->id === null) {
    header("Location: ../../auth/login.php");
    exit;
}

$ratingValue = '';
$commentValue = '';
$requestTitle = '';

if (!isset($_GET['request_id'])) {
    die("Request ID required");
}

$requestId = (int) $_GET['request_id'];

$request = $requestService->getRequestById($requestId);

if (!$request) {
    die("Request not found");
}

if ($request->status !== 'RESOLVED') {
    die("You can only review resolved requests");
}

if ($request->creator_id != $user->id) {
    die("Not allowed");
}

if (empty($request->helper_id)) {
    $_SESSION['flash_error'] = 'Review unavailable: this request has no assigned helper.';
    header('Location: ../help_request/details.php?id=' . $requestId);
    exit;
}

$requestTitle = (string) ($request->title ?? 'Request');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ratingValue = (string) ($_POST['rating'] ?? '');
    $commentValue = trim((string) ($_POST['comment'] ?? ''));

    try {

$review = new Review(
    null,
    (int) $_SESSION['user']->id,
    (int) $_POST['rating'],
    trim($_POST['comment']),
    $requestId
);
        $reviewService->createReview($review);

        header("Location: ../help_request/details.php?id=" . $requestId);
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
    <title>Leave Review — PeerSync</title>

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

<div class="flex min-h-screen items-center justify-center px-4 py-10">

    <div class="w-full max-w-2xl rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur-xl lg:p-8">

        <!-- HEADER -->
        <div class="mb-8">

            <p class="text-xs uppercase tracking-[0.3em] text-cyan-300/70">
                Review
            </p>

            <h1 class="mt-2 text-3xl font-bold">
                Rate the helper
            </h1>

            <p class="mt-2 text-gray-400">
                Reviewing: <span class="text-white font-medium"><?= htmlspecialchars($requestTitle) ?></span>
            </p>

        </div>

        <!-- ERROR -->
        <?php if ($error): ?>
            <div class="mb-5 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" class="space-y-5">

            <!-- STAR RATING -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">

                <div class="flex justify-between items-center mb-3">
                    <p class="text-gray-400 text-sm">Your rating</p>
                    <span id="rating-badge"
                          class="bg-cyan-500/15 border border-cyan-500/25 text-cyan-300 text-xs px-3 py-1 rounded-full">
                        Select a rating
                    </span>
                </div>

                <div class="flex gap-2 mb-2" id="stars">
                    <span class="star text-4xl cursor-pointer text-white/15 transition-all duration-150 select-none" data-val="1">★</span>
                    <span class="star text-4xl cursor-pointer text-white/15 transition-all duration-150 select-none" data-val="2">★</span>
                    <span class="star text-4xl cursor-pointer text-white/15 transition-all duration-150 select-none" data-val="3">★</span>
                    <span class="star text-4xl cursor-pointer text-white/15 transition-all duration-150 select-none" data-val="4">★</span>
                    <span class="star text-4xl cursor-pointer text-white/15 transition-all duration-150 select-none" data-val="5">★</span>
                </div>

                <p id="star-hint" class="text-xs text-gray-500 h-4"></p>

                <!-- hidden input carries the actual value -->
                <input type="hidden" name="rating" id="rating-input" value="<?= htmlspecialchars($ratingValue) ?>" required>

            </div>

            <!-- COMMENT -->
            <div>
                <label class="block text-sm text-gray-400 mb-2">
                    <i class="fa-regular fa-comment mr-1"></i> Comment
                </label>
                <textarea name="comment"
                          rows="5"
                          placeholder="Share your experience with this helper..."
                          class="w-full rounded-2xl border border-white/10 bg-[#020617]/60 px-4 py-3 text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 resize-none"
                          required><?= htmlspecialchars($commentValue) ?></textarea>
            </div>

            <!-- BUTTONS -->
            <div class="flex gap-3 pt-1">

                <a href="../help_request/details.php?id=<?= $requestId ?>"
                   class="flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 transition py-3 px-6 rounded-2xl text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>

                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:scale-[1.02] active:scale-[0.98] transition py-3 rounded-2xl font-semibold shadow-lg">
                    <i class="fa-solid fa-paper-plane"></i>
                    Submit Review
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    const labels   = ['Poor', 'Fair', 'Good', 'Very good', 'Excellent'];
    const stars    = document.querySelectorAll('.star');
    const badge    = document.getElementById('rating-badge');
    const hint     = document.getElementById('star-hint');
    const input    = document.getElementById('rating-input');
    let   current  = parseInt(input.value) || 0;

    function render(val) {
        stars.forEach(s => {
            const active = +s.dataset.val <= val;
            s.style.color = active ? '#facc15' : 'rgba(255,255,255,0.15)';
        });
        if (val > 0) {
            badge.textContent = val + ' / 5 — ' + labels[val - 1];
            hint.textContent  = labels[val - 1];
        } else {
            badge.textContent = 'Select a rating';
            hint.textContent  = '';
        }
    }

    stars.forEach(s => {
        s.addEventListener('mouseenter', () => render(+s.dataset.val));
        s.addEventListener('mouseleave', () => render(current));
        s.addEventListener('click', () => {
            current      = +s.dataset.val;
            input.value  = current;
            render(current);
        });
    });

    // restore saved value on page load (e.g. after validation error)
    render(current);
</script>

</body>
</html>