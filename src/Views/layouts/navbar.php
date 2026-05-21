<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$user = ps_user();
$roleLabel = ps_role_label($user);
$userName = (string) ($user['name'] ?? 'PeerSync User');
?>


<header class="sticky top-0 z-30 border-b border-white/10 bg-white/80 backdrop-blur-xl dark:bg-slate-950/80">
	<div class="mx-auto flex max-w-[1800px] items-center gap-4 px-4 py-4 sm:px-6 lg:px-8 lg:pl-72">
		<button type="button" data-sidebar-toggle class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200/80 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-400 hover:text-brand-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 lg:hidden">
			<span class="sr-only">Open sidebar</span>
			<?= ps_icon('menu') ?>
		</button>

		<div class="hidden flex-1 items-center gap-3 md:flex">
			<div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-indigo-600 text-sm font-black text-white shadow-glow">PS</div>
			<div>
				<p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">PeerSync</p>
				<h1 class="text-lg font-extrabold text-slate-900 dark:text-white">Collaborative tutoring workspace</h1>
			</div>
		</div>

		<label class="hidden flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 shadow-sm transition focus-within:border-brand-400 focus-within:ring-4 focus-within:ring-brand-500/10 dark:border-white/10 dark:bg-white/5 dark:text-slate-400 md:flex">
			<?= ps_icon('search') ?>
			<input type="search" placeholder="Search requests, sessions, skills..." class="w-full bg-transparent outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
		</label>

		<div class="ml-auto flex items-center gap-3">
			<button type="button" data-theme-toggle class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-400 hover:text-brand-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
				<span class="sr-only">Toggle theme</span>
				<?= ps_icon('theme') ?>
			</button>

			<button type="button" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-400 hover:text-brand-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
				<span class="sr-only">Notifications</span>
				<?= ps_icon('bell') ?>
				<span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-white dark:ring-slate-950"></span>
			</button>

			<div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm dark:border-white/10 dark:bg-white/5">
				<div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 text-sm font-bold text-white">
					<?= ps_escape(ps_initials($userName)) ?>
				</div>
				<div class="hidden sm:block">
					<p class="text-sm font-bold text-slate-900 dark:text-white"><?= ps_escape($userName) ?></p>
					<p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-500"><?= ps_escape($roleLabel) ?></p>
				</div>
			</div>
		</div>
	</div>
</header>
