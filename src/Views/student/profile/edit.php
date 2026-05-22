<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/AuthService.php';

use Src\Services\AuthService;

$user = $_SESSION['user'];

if (is_array($user)) {
    $user = (object) $user;
}

$service     = new AuthService();
$currentUser = $service->getUserById($user->id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile — PeerSync</title>

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

    <div class="w-full max-w-xl">

        <!-- HEADER -->
        <div class="mb-8 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-cyan-300/70 mb-2">
                <i class="fa-solid fa-user mr-2"></i>Account
            </p>
            <h1 class="text-4xl font-bold">Edit Profile</h1>
            <p class="text-gray-400 mt-2">Update your name and email address</p>
        </div>

        <!-- AVATAR PREVIEW -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600
                        flex items-center justify-center text-3xl font-bold">
                <?= strtoupper(substr($currentUser->name, 0, 1)) ?>
            </div>
        </div>

        <!-- CARD -->
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-8">

            <form action="update.php" method="POST" class="space-y-5">

                <!-- NAME -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">
                        <i class="fa-solid fa-user mr-1"></i> Name
                    </label>
                    <input type="text"
                           name="name"
                           value="<?= htmlspecialchars($currentUser->name) ?>"
                           placeholder="Your full name"
                           class="w-full rounded-2xl border border-white/10 bg-[#020617]/60 px-4 py-3
                                  text-white placeholder:text-slate-500 outline-none transition
                                  focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20"
                           required>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">
                        <i class="fa-regular fa-envelope mr-1"></i> Email
                    </label>
                    <input type="email"
                           name="email"
                           value="<?= htmlspecialchars($currentUser->email) ?>"
                           placeholder="your@email.com"
                           class="w-full rounded-2xl border border-white/10 bg-[#020617]/60 px-4 py-3
                                  text-white placeholder:text-slate-500 outline-none transition
                                  focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20"
                           required>
                </div>

                <!-- DIVIDER -->
                <div class="border-t border-white/10"></div>

                <!-- BUTTONS -->
                <div class="flex gap-3 pt-1">

                    <a href="index.php"
                       class="flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20
                              transition py-3 px-6 rounded-2xl text-sm font-medium">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>

                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2
                                   bg-gradient-to-r from-cyan-500 to-blue-600
                                   hover:scale-[1.02] active:scale-[0.98] transition
                                   py-3 rounded-2xl font-semibold shadow-lg">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

        <!-- FOOTER LINK -->
        <p class="text-center text-gray-600 text-sm mt-6">
            Back to
            <a href="www.google.com" class="text-cyan-400 hover:text-cyan-300 transition">My Profile</a>
        </p>

    </div>

</div>

</body>
</html>