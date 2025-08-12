<?php

namespace Core;

/**
 * Class Controller
 *
 * Base controller class providing common response utilities for controllers.
 * This class offers simple methods for rendering views, redirecting, and returning JSON responses.
 * Controllers in your application should extend this class to inherit these helper methods.
 */
class Controller
{
    /**
     * Render a PHP view template with optional data.
     *
     * This method delegates to the View class to load the specified view file
     * and pass variables to it for rendering HTML.
     *
     * @param string $view The dot-notated view name (e.g., 'home.index' maps to app/Views/home/index.php)
     * @param array $data Associative array of data to extract and make available to the view
     */
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    /**
     * Redirect the user to another URI and terminate the current script.
     *
     * This is commonly used after POST requests or actions that change application state,
     * to prevent duplicate form submissions and update the browser's address bar.
     *
     * @param string $uri The URI or URL to redirect to (e.g., '/login', '/dashboard')
     */
    protected function redirect(string $uri): void
    {
        header('Location: ' . $uri);
        exit;
    }

    /**
     * Send a JSON response with the given data and HTTP status code, then terminate.
     *
     * Useful for API endpoints or AJAX responses where the client expects JSON.
     * The JSON_UNESCAPED_UNICODE option ensures Unicode characters are not escaped.
     *
     * @param mixed $data Data to encode as JSON (array, object, etc.)
     * @param int $status HTTP status code to send (default 200 OK)
     */
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
