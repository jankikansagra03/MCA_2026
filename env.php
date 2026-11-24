<?php
/**
 * env.php - Lightweight .env loader & helper for MCA_2026.
 * Reads environment variables from the .env file without external dependencies.
 */

if (!function_exists('loadEnv')) {
    /**
     * Load environment variables from a .env file.
     *
     * @param string $filePath
     * @return bool
     */
    function loadEnv(string $filePath = __DIR__ . '/.env'): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $lines = @file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            // Must contain '='
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key   = trim($key);
                $value = trim($value);

                // Strip surrounding single or double quotes
                if (
                    (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))
                ) {
                    $value = substr($value, 1, -1);
                }

                // Populate putenv, $_ENV, and $_SERVER
                putenv("{$key}={$value}");
                $_ENV[$key]    = $value;
                $_SERVER[$key] = $value;
            }
        }

        return true;
    }
}

if (!function_exists('env')) {
    /**
     * Get an environment variable with an optional default fallback.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        $val = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($val === false || $val === null) {
            return $default;
        }

        // Handle boolean and null representations
        $lower = strtolower(trim((string) $val));
        if ($lower === 'true' || $lower === '(true)')   return true;
        if ($lower === 'false' || $lower === '(false)') return false;
        if ($lower === 'null' || $lower === '(null)')   return null;
        if ($lower === 'empty' || $lower === '(empty)') return '';

        return $val;
    }
}

// Automatically load the workspace .env on inclusion
loadEnv(__DIR__ . '/.env');
