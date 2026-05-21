<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$pageTitle = $pageTitle ?? 'PeerSync';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light dark">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title><?= ps_escape($pageTitle) ?></title>
<script>
    tailwind = window.tailwind || {};
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                boxShadow: {
                    glow: '0 24px 80px rgba(59, 130, 246, 0.22)'
                },
                colors: {
                    brand: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        200: '#bfdbfe',
                        300: '#93c5fd',
                        400: '#60a5fa',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',
                        900: '#1e3a8a'
                    }
                }
            }
        }
    };

    (function () {
        const saved = localStorage.getItem('peersync-theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const useDark = saved ? saved === 'dark' : prefersDark;
        document.documentElement.classList.toggle('dark', useDark);
    })();
</script>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    :root {
        color-scheme: light dark;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
</style>
