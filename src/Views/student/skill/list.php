<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../../Services/SkillService.php';

use Src\Services\SkillService;

$user = $_SESSION['user'];

if (is_array($user)) {
    $user = (object) $user;
}

$service = new SkillService();

$allSkills      = $service->getAllSkills();
$masteredSkills = $service->getMasteredSkills($user->id);
$learningSkills = $service->getLearningSkills($user->id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills — PeerSync</title>

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
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-300/70 mb-1">
                    <i class="fa-solid fa-layer-group mr-2"></i>Skills
                </p>
                <h1 class="text-4xl font-bold">My Skills</h1>
                <p class="text-gray-400 mt-2">Manage your mastered and learning skills</p>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- ADD SKILL -->
                <a href="create.php"
                   class="flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600
                          hover:scale-105 transition px-5 py-3 rounded-2xl font-semibold shadow-lg">
                    <i class="fa-solid fa-plus"></i>
                    Add Skill
                </a>

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

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- TOTAL -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Total Skills</p>
                        <h2 class="text-4xl font-bold mt-2 text-cyan-400">
                            <?= count($masteredSkills) + count($learningSkills) ?>
                        </h2>
                    </div>
                    <i class="fa-solid fa-layer-group text-3xl text-cyan-400"></i>
                </div>
            </div>

            <!-- MASTERED -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Mastered</p>
                        <h2 class="text-4xl font-bold mt-2 text-green-400">
                            <?= count($masteredSkills) ?>
                        </h2>
                    </div>
                    <i class="fa-solid fa-circle-check text-3xl text-green-400"></i>
                </div>
            </div>

            <!-- LEARNING -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400">Learning</p>
                        <h2 class="text-4xl font-bold mt-2 text-purple-400">
                            <?= count($learningSkills) ?>
                        </h2>
                    </div>
                    <i class="fa-solid fa-book-open text-3xl text-purple-400"></i>
                </div>
            </div>

        </div>

        <!-- SKILLS GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- MASTERED SKILLS -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-400"></i>
                        Mastered Skills
                    </h2>
                    <span class="bg-green-500/20 border border-green-500/30 text-green-300
                                 text-xs px-3 py-1 rounded-full font-semibold">
                        <?= count($masteredSkills) ?>
                    </span>
                </div>

                <?php if (empty($masteredSkills)): ?>
                    <div class="rounded-2xl border border-dashed border-white/10 bg-white/5
                                p-8 text-center">
                        <i class="fa-solid fa-circle-check text-3xl text-gray-600 mb-3 block"></i>
                        <p class="text-gray-400 text-sm">No mastered skills yet.</p>
                        <a href="create.php"
                           class="inline-block mt-3 text-cyan-400 hover:text-cyan-300 text-sm">
                            + Add your first skill
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($masteredSkills as $skill): ?>
                            <div class="flex items-center gap-2 bg-green-500/15 border border-green-500/25
                                        text-green-300 px-4 py-2 rounded-full hover:bg-green-500/25 transition group">

                                <i class="fa-solid fa-check text-xs"></i>

                                <span class="font-medium text-sm">
                                    <?= htmlspecialchars($skill->name) ?>
                                </span>

                                <a href="delete.php?skill_id=<?= $skill->id ?>&type=mastered"
                                   class="text-green-500/50 hover:text-red-400 transition ml-1"
                                   title="Remove skill"
                                   onclick="return confirm('Remove <?= htmlspecialchars($skill->name) ?> from mastered skills?')">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </a>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- LEARNING SKILLS -->
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-6">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-purple-400"></i>
                        Learning Skills
                    </h2>
                    <span class="bg-purple-500/20 border border-purple-500/30 text-purple-300
                                 text-xs px-3 py-1 rounded-full font-semibold">
                        <?= count($learningSkills) ?>
                    </span>
                </div>

                <?php if (empty($learningSkills)): ?>
                    <div class="rounded-2xl border border-dashed border-white/10 bg-white/5
                                p-8 text-center">
                        <i class="fa-solid fa-book-open text-3xl text-gray-600 mb-3 block"></i>
                        <p class="text-gray-400 text-sm">No learning skills yet.</p>
                        <a href="create.php"
                           class="inline-block mt-3 text-cyan-400 hover:text-cyan-300 text-sm">
                            + Add a skill to learn
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($learningSkills as $skill): ?>
                            <div class="flex items-center gap-2 bg-purple-500/15 border border-purple-500/25
                                        text-purple-300 px-4 py-2 rounded-full hover:bg-purple-500/25 transition group">

                                <i class="fa-solid fa-book-open text-xs"></i>

                                <span class="font-medium text-sm">
                                    <?= htmlspecialchars($skill->name) ?>
                                </span>

                                <a href="delete.php?skill_id=<?= $skill->id ?>&type=learning"
                                   class="text-purple-500/50 hover:text-red-400 transition ml-1"
                                   title="Remove skill"
                                   onclick="return confirm('Remove <?= htmlspecialchars($skill->name) ?> from learning skills?')">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </a>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </main>

</div>

</body>
</html>