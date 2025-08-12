<?php

namespace Core;

use Core\Request;

/**
 * Abstract Middleware class
 *
 * Provides a contract for all middleware implementations to handle HTTP requests.
 * Middleware classes must implement the handle method, receiving the Request object.
 */
abstract class Middleware
{
    /**
     * Handle the incoming request.
     *
     * Middleware logic should be implemented in this method.
     * It can modify the request, perform checks, and decide whether to continue the request cycle.
     *
     * @param Request $request The current HTTP request instance
     * @return void
     */
    abstract public function handle(Request $request): void;
}
