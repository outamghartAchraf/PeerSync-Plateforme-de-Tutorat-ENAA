<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
?>

<footer class="border-t border-white/10 bg-white/60 py-6 text-sm text-slate-500 backdrop-blur-xl dark:bg-slate-950/60 dark:text-slate-400">
    <div class="mx-auto flex max-w-[1800px] flex-col gap-3 px-4 sm:px-6 lg:px-8 lg:pl-80 md:flex-row md:items-center md:justify-between">
        <p>&copy; <?= date('Y') ?> PeerSync - ENAA internal tutoring platform.</p>
        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Built for fast collaboration</p>
    </div>
</footer>

<script>
    (function () {
        const root = document.documentElement;
        const sidebar = document.querySelector('[data-sidebar]');
        const overlay = document.querySelector('[data-sidebar-overlay]');

        const openSidebar = () => {
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeSidebar = () => {
            if (!sidebar || !overlay) return;
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => button.addEventListener('click', openSidebar));
        document.querySelectorAll('[data-sidebar-close]').forEach((button) => button.addEventListener('click', closeSidebar));
        overlay?.addEventListener('click', closeSidebar);

        const themeButton = document.querySelector('[data-theme-toggle]');
        const applyTheme = (mode) => {
            root.classList.toggle('dark', mode === 'dark');
            localStorage.setItem('peersync-theme', mode);
        };

        themeButton?.addEventListener('click', () => {
            const mode = root.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(mode);
        });

        document.querySelectorAll('[data-modal-open]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = document.getElementById(button.getAttribute('data-modal-open'));
                target?.classList.remove('hidden');
                target?.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = button.closest('[data-modal]');
                modal?.classList.add('hidden');
                modal?.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-request-search]').forEach((input) => {
            const filterCards = () => {
                const search = String(input.value || '').toLowerCase().trim();
                const status = (document.querySelector('[data-request-status]')?.value || '').toLowerCase();
                const technology = (document.querySelector('[data-request-technology]')?.value || '').toLowerCase();

                document.querySelectorAll('[data-request-card]').forEach((card) => {
                    const cardStatus = (card.getAttribute('data-status') || '').toLowerCase();
                    const cardTechnology = (card.getAttribute('data-technology') || '').toLowerCase();
                    const cardText = (card.textContent || '').toLowerCase();
                    const matchesSearch = search === '' || cardText.includes(search);
                    const matchesStatus = status === '' || status === 'all' || cardStatus === status;
                    const matchesTechnology = technology === '' || technology === 'all' || cardTechnology === technology;

                    card.classList.toggle('hidden', !(matchesSearch && matchesStatus && matchesTechnology));
                });
            };

            input.addEventListener('input', filterCards);
            document.querySelectorAll('[data-request-status], [data-request-technology]').forEach((control) => {
                control.addEventListener('change', filterCards);
            });
        });

        document.querySelectorAll('[data-toast]').forEach((toast) => {
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 250);
            }, 3500);
        });
    })();
</script>
