<?php

if (!function_exists('app_version')) {
    function app_version(): string
    {
        $path = base_path('bootstrap/cache/VERSION');

        if (file_exists($path)) {
            return trim(file_get_contents($path));
        }

        // fallback if file missing (e.g. local dev)
        return trim(exec('git describe --tags --always --abbrev=4 2>/dev/null')) ?: 'unknown';
    }
}
