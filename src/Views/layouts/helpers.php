<?php

declare(strict_types=1);

if (!function_exists('ps_escape')) {
    function ps_escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('ps_base_url')) {
    function ps_base_url(): string
    {
        static $baseUrl = null;

        if ($baseUrl !== null) {
            return $baseUrl;
        }

        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptName !== '' && preg_match('~/src/Views/.*$~', $scriptName)) {
            $baseUrl = preg_replace('~/src/Views/.*$~', '', $scriptName) ?: '';
        } else {
            $baseUrl = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
        }

        return $baseUrl === '' ? '' : $baseUrl;
    }
}

if (!function_exists('ps_url')) {
    function ps_url(string $path): string
    {
        return rtrim(ps_base_url(), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('ps_user')) {
    function ps_user(): array
    {
        return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : [];
    }
}

if (!function_exists('ps_role_label')) {
    function ps_role_label(?array $user = null): string
    {
        $user = $user ?? ps_user();
        $role = strtolower(trim((string) ($user['role_name'] ?? $user['role'] ?? 'student')));

        return in_array($role, ['admin', 'tutor', 'teacher'], true) ? 'Tutor' : 'Student';
    }
}

if (!function_exists('ps_role_slug')) {
    function ps_role_slug(?array $user = null): string
    {
        return strtolower(ps_role_label($user));
    }
}

if (!function_exists('ps_initials')) {
    function ps_initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : 'PS';
    }
}

if (!function_exists('ps_time_ago')) {
    function ps_time_ago(?string $dateTime): string
    {
        if (!$dateTime) {
            return 'Just now';
        }

        $time = strtotime($dateTime);
        if ($time === false) {
            return $dateTime;
        }

        $diff = time() - $time;
        if ($diff < 60) {
            return 'Just now';
        }

        if ($diff < 3600) {
            return (int) floor($diff / 60) . 'm ago';
        }

        if ($diff < 86400) {
            return (int) floor($diff / 3600) . 'h ago';
        }

        if ($diff < 604800) {
            return (int) floor($diff / 86400) . 'd ago';
        }

        return date('M j, Y', $time);
    }
}

if (!function_exists('ps_status_badge')) {
    function ps_status_badge(string $status): string
    {
        $status = strtolower(trim($status));
        $map = [
            'pending' => 'bg-amber-500/15 text-amber-300 ring-1 ring-amber-400/30',
            'assigned' => 'bg-sky-500/15 text-sky-300 ring-1 ring-sky-400/30',
            'resolved' => 'bg-emerald-500/15 text-emerald-300 ring-1 ring-emerald-400/30',
        ];

        $class = $map[$status] ?? 'bg-white/10 text-slate-300 ring-1 ring-white/10';

        return sprintf(
            '<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] %s">%s</span>',
            $class,
            ps_escape($status !== '' ? $status : 'unknown')
        );
    }
}

if (!function_exists('ps_technology_chip')) {
    function ps_technology_chip(string $label): string
    {
        $label = trim($label);

        return sprintf(
            '<span class="inline-flex items-center rounded-full border border-white/10 bg-slate-900/70 px-3 py-1 text-xs font-semibold text-slate-200">%s</span>',
            ps_escape($label)
        );
    }
}

if (!function_exists('ps_icon')) {
    function ps_icon(string $name): string
    {
        $icons = [
            'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6.75A2.25 2.25 0 016 4.5h4.5A2.25 2.25 0 0112.75 6.75v10.5A2.25 2.25 0 0110.5 19.5H6A2.25 2.25 0 013.75 17.25V6.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 4.5h1.5A2.25 2.25 0 0119.5 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-1.5"/>',
            'requests' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 3.75h8.25a2.25 2.25 0 012.25 2.25v12.75l-4.5-3-4.5 3-4.5-3V6a2.25 2.25 0 012.25-2.25Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 8.25h4.5M8.25 11.25h4.5"/>',
            'sessions' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2"/><circle cx="12" cy="12" r="8.25" fill="none" stroke="currentColor" stroke-width="1.8"/>',
            'skills' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3.75l8.25 4.5-8.25 4.5-8.25-4.5 8.25-4.5Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12l7.5 4.5 7.5-4.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 16.5l7.5 4.5 7.5-4.5"/>',
            'reviews' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.48 3.499a.75.75 0 011.04 0l2.214 2.24 3.094.45a.75.75 0 01.416 1.278l-2.24 2.183.528 3.082a.75.75 0 01-1.088.79L12 12.959l-2.764 1.473a.75.75 0 01-1.088-.79l.528-3.082-2.24-2.183a.75.75 0 01.416-1.278l3.094-.45 2.214-2.24Z"/>',
            'profile' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 19.5a8.25 8.25 0 1115 0"/>',
            'logout' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12H3.75"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8.25L5.25 12 9 15.75"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 4.5h1.5A2.25 2.25 0 0119.5 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-1.5"/>',
            'bell' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.25 16.5a2.25 2.25 0 11-4.5 0"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 7.5a5.25 5.25 0 1110.5 0c0 4.125 1.5 5.25 1.5 5.25h-13.5s1.5-1.125 1.5-5.25Z"/>',
            'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15.75 15.75-3.5-3.5m1.5-3.25a5.5 5.5 0 11-11 0 5.5 5.5 0 0111 0Z"/>',
            'menu' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>',
            'close' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6L6 18"/>',
            'theme' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12.79A9 9 0 1111.21 3a7.5 7.5 0 009.79 9.79Z"/>',
            'arrow-right' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.25 12h13.5m0 0-4.5-4.5m4.5 4.5-4.5 4.5"/>',
        ];

        $markup = $icons[$name] ?? $icons['dashboard'];

        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">' . $markup . '</svg>';
    }
}

if (!function_exists('ps_nav_items')) {
    function ps_nav_items(): array
    {
        return [
            ['label' => 'Dashboard', 'path' => 'src/Views/student/dashboard.php', 'icon' => 'dashboard'],
            ['label' => 'Help Requests', 'path' => 'src/Views/student/help_requests.php', 'icon' => 'requests'],
            ['label' => 'My Sessions', 'path' => 'src/Views/student/sessions.php', 'icon' => 'sessions'],
            ['label' => 'Skills', 'path' => 'src/Views/student/skills.php', 'icon' => 'skills'],
            ['label' => 'Reviews', 'path' => 'src/Views/student/reviews.php', 'icon' => 'reviews'],
            ['label' => 'Profile', 'path' => 'src/Views/student/profile.php', 'icon' => 'profile'],
            ['label' => 'Logout', 'path' => 'src/Views/auth/logout.php', 'icon' => 'logout'],
        ];
    }
}

if (!function_exists('ps_is_active_nav')) {
    function ps_is_active_nav(string $path): bool
    {
        $current = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        return $current !== '' && $current === basename($path);
    }
}

if (!function_exists('ps_star_rating')) {
    function ps_star_rating(int $rating): string
    {
        $rating = max(0, min(5, $rating));
        $stars = '';

        for ($index = 1; $index <= 5; $index++) {
            $stars .= $index <= $rating
                ? '<svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-amber-400"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.664 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.285-3.957Z"/></svg>'
                : '<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" class="h-5 w-5 text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.664 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.285-3.957Z"/></svg>';
        }

        return '<div class="flex items-center gap-1">' . $stars . '</div>';
    }
}
