<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$user = ps_user();
$roleLabel = ps_role_label($user);
$userName = (string) ($user['name'] ?? 'PeerSync User');
$points = (int) ($user['points'] ?? 0);
$items = ps_nav_items();
?>

<div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

<aside data-sidebar class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-white/10 bg-slate-950/95 px-4 py-5 text-white backdrop-blur-xl transition-transform duration-300 lg:translate-x-0">
	<div class="flex items-center justify-between gap-4 px-2 pb-6">
		<div class="flex items-center gap-3">
			<div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-indigo-600 text-lg font-black shadow-glow">PS</div>
			<div>
				<p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">PeerSync</p>
				<h2 class="text-lg font-extrabold text-white">ENAA Bootcamp</h2>
			</div>
		</div>

		<button type="button" data-sidebar-close class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10 lg:hidden">
			<span class="sr-only">Close sidebar</span>
			<?= ps_icon('close') ?>
		</button>
	</div>

	<div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-glow">
		<div class="flex items-center gap-3">
			<div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-indigo-600 text-lg font-black text-white">
				<?= ps_escape(ps_initials($userName)) ?>
			</div>
			<div>
				<p class="text-sm font-bold text-white"><?= ps_escape($userName) ?></p>
				<p class="text-xs uppercase tracking-[0.2em] text-brand-300"><?= ps_escape($roleLabel) ?></p>
			</div>
		</div>

		<div class="mt-4 flex items-center gap-3 text-sm text-slate-300">
			<div class="rounded-2xl bg-white/10 px-3 py-2">
				<span class="block text-xs uppercase tracking-[0.2em] text-slate-400">Points</span>
				<span class="text-lg font-extrabold text-white"><?= number_format($points) ?></span>
			</div>
			<div class="rounded-2xl bg-white/10 px-3 py-2">
				<span class="block text-xs uppercase tracking-[0.2em] text-slate-400">Mode</span>
				<span class="text-lg font-extrabold text-white">Light/Dark</span>
			</div>
		</div>
	</div>

	<nav class="mt-6 flex-1 space-y-2 overflow-y-auto pr-1">
		<?php foreach ($items as $item): ?>
			<?php $active = ps_is_active_nav($item['path']); ?>
			<a href="<?= ps_escape(ps_url($item['path'])) ?>" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition <?= $active ? 'bg-gradient-to-r from-brand-500 to-indigo-600 text-white shadow-glow' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?>">
				<span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl <?= $active ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' ?>">
					<?= ps_icon($item['icon']) ?>
				</span>
				<span><?= ps_escape($item['label']) ?></span>
				<?php if ($item['label'] === 'Help Requests'): ?>
					<span class="ml-auto rounded-full bg-amber-400/15 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-amber-200">Live</span>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<div class="mt-6 rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 to-white/5 p-4">
		<p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Quick focus</p>
		<h3 class="mt-2 text-lg font-extrabold text-white">Ask, match, resolve.</h3>
		<p class="mt-2 text-sm leading-6 text-slate-300">Use the request board to connect with peers, accept sessions, and track progress end to end.</p>
		<a href="<?= ps_escape(ps_url('src/Views/student/help_requests.php')) ?>" class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:-translate-y-0.5">
			Open board
			<?= ps_icon('arrow-right') ?>
		</a>
	</div>
</aside>
