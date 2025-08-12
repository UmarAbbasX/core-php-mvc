<?php

namespace App\Middleware;

use Core\Request;
use Core\Middleware;

/**
 * Class AuthMiddleware
 *
 * Middleware to handle authentication and guest access control.
 * Redirects users based on their authentication status and middleware mode.
 */
class AuthMiddleware extends Middleware
{
    /**
     * The mode in which the middleware operates.
     * Possible values: 'auth' (restrict to authenticated users),
     * 'guest' (restrict to guests only).
     *
     * @var string
     */
    protected string $mode = 'auth';

    /**
     * AuthMiddleware constructor.
     *
     * @param string $mode Mode for this middleware ('auth' or 'guest').
     */
    public function __construct(string $mode = 'auth')
    {
        $this->mode = $mode;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request The HTTP request object.
     * @return void
     */
    public function handle(Request $request): void
    {
        // Check if the user is logged in
        $isLoggedIn = isset($_SESSION['user']);

        // If mode is 'auth' but user is not logged in, redirect to login
        if ($this->mode === 'auth' && !$isLoggedIn) {
            header('Location: /login');
            exit;
        }

        // If mode is 'guest' but user is logged in, redirect to dashboard
        if ($this->mode === 'guest' && $isLoggedIn) {
            header('Location: /dashboard');
            exit;
        }
    }
}
