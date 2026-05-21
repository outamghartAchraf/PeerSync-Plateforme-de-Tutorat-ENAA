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

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $technology = trim($_POST['technology'] ?? '');

    try {

        $helpRequest = new HelpRequest(
        
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
    <title>Create Help Request</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">

<div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.18),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.12),_transparent_30%)]"></div>

<div class="mx-auto flex min-h-screen max-w-4xl items-center px-4 py-10 sm:px-6 lg:px-8">

    <div class="w-full rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur lg:p-8">

        <!-- HEADER -->
        <div class="mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-300/70">
                Help Request
            </p>

            <h1 class="mt-2 text-3xl font-bold">
                Create a new request
            </h1>

            <p class="mt-2 text-slate-400">
                Provide enough detail for another student to help quickly.
            </p>
        </div>

        <!-- ERROR -->
        <?php if ($error): ?>
            <div class="mb-5 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST" class="space-y-4">

            <input type="text"
                   name="title"
                   value="<?= htmlspecialchars($title) ?>"
                   placeholder="Title"
                   class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 outline-none focus:border-cyan-400"
                   required>

            <textarea name="description"
                      rows="6"
                      placeholder="Description"
                      class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 outline-none focus:border-cyan-400"
                      required><?= htmlspecialchars($description) ?></textarea>

            <input type="text"
                   name="technology"
                   value="<?= htmlspecialchars($technology) ?>"
                   placeholder="Technology (PHP, React, SQL...)"
                   class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 outline-none focus:border-cyan-400"
                   required>

            <button type="submit"
                    class="w-full rounded-2xl bg-cyan-400 px-4 py-3 font-semibold text-slate-950 hover:bg-cyan-300">
                Create Request
            </button>

        </form>

    </div>

</div>

</body>
</html>


      