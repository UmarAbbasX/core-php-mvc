<?php

namespace Core;

/**
 * Class App
 *
 * The main application class responsible for bootstrapping the framework.
 * It loads the route definitions and dispatches the incoming HTTP request
 * through the router to the appropriate controller or action.
 */
class App
{
    /**
     * Runs the application.
     *
     * This method:
     * - Loads the route definitions from the routes file.
     * - Captures the current HTTP request.
     * - Dispatches the request to the router to find and execute the matching route.
     *
     * @throws \Exception If the routes file cannot be found.
     */
    public function run(): void
    {
        // Define the path to the route definitions file
        $routesFile = dirname(__DIR__) . '/app/Routes/web.php';

        // Ensure the routes file exists before attempting to load it
        if (!file_exists($routesFile)) {
            throw new \Exception('Routes file not found at expected location: ' . $routesFile, 500);
        }

        // Load all route declarations
        require $routesFile;

        // Capture the incoming HTTP request data
        $request = Request::capture();

        // Dispatch the request to the router to handle the response
        Router::dispatch($request);
    }
}
