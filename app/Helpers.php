<?php

use Core\Env;
use Core\Router;
use Core\Request;

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        $t = htmlspecialchars(csrf_token(), ENT_QUOTES);
        return "<input type=\"hidden\" name=\"_csrf\" value=\"{$t}\">";
    }
}

if (!function_exists('route')) {
    function route(string $name, array $params = []): string
    {
        return Router::route($name, $params);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/../' . ltrim($path, '/\\');
    }
}

// Optional: for convenience
if (!function_exists('redirect')) {
    function redirect(string $to): void
    {
        header("Location: {$to}");
        exit;
    }
}

// Remove or revise this — no longer accurate
if (!function_exists('middleware')) {
    /**
     * This is deprecated and no longer matches Middleware::handle(Request $request)
     * It's better to apply middleware in Router definitions or groups.
     */
    function middleware(string $mode = 'auth'): void
    {
        // This function no longer works unless adapted to new Middleware signature
        // It's safe to remove or rewrite for testing/debug use only
        throw new \Exception('The middleware() helper is deprecated. Use Router group middleware instead.');
    }
}

function generateCsrfToken()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}
