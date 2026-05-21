<?php
$user = $user ?? null;

$namePath = $_SERVER['PHP_SELF'];
?>

<!-- SIDEBAR -->
<aside class="w-64 h-screen fixed bg-white/5 backdrop-blur-2xl border-r border-white/10 p-6">

    <!-- LOGO -->
    <h1 class="text-3xl font-bold text-cyan-400 mb-10">
        <i class="fa-solid fa-graduation-cap"></i>
        PeerSync
    </h1>

    <!-- MENU -->
    <nav class="space-y-3">

        <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/dashboard.php"
           class="<?= ($namePath === '/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/dashboard.php')
               ? 'flex items-center gap-3 bg-white/10 text-cyan-400 px-4 py-3 rounded-2xl transition'
               : 'flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition' ?>">

            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/help_request/list.php"
           class="<?= ($namePath === '/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/help_request/list.php')
               ? 'flex items-center gap-3 bg-white/10 text-cyan-400 px-4 py-3 rounded-2xl transition'
               : 'flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition' ?>">

            <i class="fa-solid fa-list-check"></i>
            Requests
        </a>

        <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/skill/list.php"
           class="<?= ($namePath === '/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/student/skill/list.php')
               ? 'flex items-center gap-3 bg-white/10 text-cyan-400 px-4 py-3 rounded-2xl transition'
               : 'flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition' ?>">

            <i class="fa-solid fa-code"></i>
            Skill
        </a>

        <a href="#"
           class="flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition">

            <i class="fa-solid fa-circle-check"></i>
            Review
        </a>

        <a href="#"
           class="flex items-center gap-3 hover:bg-white/10 px-4 py-3 rounded-2xl transition">

            <i class="fa-solid fa-user"></i>
            Profile
        </a>

        <a href="/PeerSync-Plateforme-de-Tutorat-ENAA/src/Views/auth/logout.php"
           class="flex items-center gap-3 hover:bg-red-500/20 text-red-400 px-4 py-3 rounded-2xl transition">

            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </nav>

    <!-- USER -->
    <?php if ($user): ?>

        <div class="absolute bottom-6 left-6 right-6">

            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">

                <p class="text-sm text-gray-400">
                    Logged in as
                </p>

                <h2 class="font-bold mt-1">
                    <?= htmlspecialchars($user->name ?? 'User') ?>
                </h2>

            </div>

        </div>

    <?php endif; ?>

</aside>