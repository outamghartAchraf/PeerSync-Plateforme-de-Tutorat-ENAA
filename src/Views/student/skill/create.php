<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/SkillService.php';

use Src\Services\SkillService;

$service = new SkillService();

$skills = $service->getAllSkills();

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
    <title>Add Skill — PeerSync</title>

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
                <i class="fa-solid fa-layer-group mr-2"></i>Skills
            </p>
            <h1 class="text-4xl font-bold">Add a Skill</h1>
            <p class="text-gray-400 mt-2">Track what you know and what you're learning</p>
        </div>

        <!-- CARD -->
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-8">

            <form action="store.php" method="POST" class="space-y-5">

                <!-- SKILL SELECT -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">
                        <i class="fa-solid fa-code mr-1"></i> Select Skill
                    </label>
                    <select name="skill_id"
                            class="w-full bg-[#020617]/60 border border-white/10 rounded-2xl px-4 py-3
                                   text-white outline-none transition focus:border-cyan-400
                                   focus:ring-2 focus:ring-cyan-400/20 cursor-pointer">
                        <?php foreach ($skills as $skill): ?>
                            <option value="<?= $skill->id ?>" class="bg-[#0f172a]">
                                <?= htmlspecialchars($skill->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- TYPE SELECT -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">
                        <i class="fa-solid fa-tag mr-1"></i> Type
                    </label>
                    <select name="type"
                            class="w-full bg-[#020617]/60 border border-white/10 rounded-2xl px-4 py-3
                                   text-white outline-none transition focus:border-cyan-400
                                   focus:ring-2 focus:ring-cyan-400/20 cursor-pointer">
                        <option value="mastered" class="bg-[#0f172a]">
                            ✓ Mastered
                        </option>
                        <option value="learning" class="bg-[#0f172a]">
                            ✦ Learning
                        </option>
                    </select>
                </div>

                <!-- PREVIEW BADGE -->
                <div class="bg-white/5 border border-white/10 rounded-2xl px-4 py-3
                            flex items-center gap-3">
                    <i class="fa-solid fa-eye text-gray-500 text-sm"></i>
                    <span class="text-gray-400 text-sm">Preview:</span>
                    <span id="preview-badge"
                          class="bg-green-500/15 border border-green-500/25 text-green-300
                                 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span id="preview-label">Mastered</span>
                    </span>
                </div>

                <!-- DIVIDER -->
                <div class="border-t border-white/10"></div>

                <!-- BUTTONS -->
                <div class="flex gap-3 pt-1">

                    <a href="list.php"
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
                        <i class="fa-solid fa-plus"></i>
                        Add Skill
                    </button>

                </div>

            </form>

        </div>

        <!-- FOOTER LINK -->
        <p class="text-center text-gray-600 text-sm mt-6">
            View all your skills in
            <a href="list.php" class="text-cyan-400 hover:text-cyan-300 transition">My Skills</a>
        </p>

    </div>

</div>

<script>
    const typeSelect   = document.querySelector('select[name="type"]');
    const badge        = document.getElementById('preview-badge');
    const label        = document.getElementById('preview-label');

    const styles = {
        mastered: {
            badge: 'bg-green-500/15 border border-green-500/25 text-green-300 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1',
            icon:  'fa-check',
            text:  'Mastered',
        },
        learning: {
            badge: 'bg-purple-500/15 border border-purple-500/25 text-purple-300 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1',
            icon:  'fa-book-open',
            text:  'Learning',
        },
    };

    typeSelect.addEventListener('change', () => {
        const s          = styles[typeSelect.value] || styles.mastered;
        badge.className  = s.badge;
        badge.innerHTML  = `<i class="fa-solid ${s.icon} text-xs"></i><span>${s.text}</span>`;
    });
</script>

</body>
</html>