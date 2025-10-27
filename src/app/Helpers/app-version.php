<?php

if (! function_exists('app_version')) {
    function app_version(): string
    {
        $path = base_path('bootstrap/cache/APP_VERSION');

        if (file_exists($path)) {
            return trim((string) file_get_contents($path));
        }

        // fallback if file missing (e.g. local dev)
        return trim((string) exec('git describe --tags --always --abbrev=4 2>/dev/null')) ?: 'unknown';
    }
}
