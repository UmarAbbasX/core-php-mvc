<?php

namespace Core;

/**
 * Class ErrorHandler
 *
 * Registers and manages error and exception handling for the application.
 * Converts PHP errors to exceptions for unified handling.
 * Provides custom error page rendering and supports debug mode for detailed output.
 */
class ErrorHandler
{
    /**
     * Registers global error, exception, and shutdown handlers.
     *
     * Configures error reporting and display settings based on environment configuration.
     *
     * @return void
     */
    public static function register(): void
    {
        // Report all errors and warnings
        error_reporting(E_ALL);

        // Set handlers for errors, exceptions, and shutdown
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);

        // Enable or disable PHP native error display based on debug flag
        $debug = Env::get('APP_DEBUG', 'false') === 'true';
        ini_set('display_errors', $debug ? '1' : '0');
    }

    /**
     * Convert PHP errors to ErrorException to unify error handling.
     *
     * @param int $errno The level of the error raised
     * @param string $errstr The error message
     * @param string $errfile The filename the error was raised in
     * @param int $errline The line number the error was raised at
     * @return bool Never returns, throws exception instead
     * @throws \ErrorException
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Handle uncaught exceptions.
     *
     * Renders a friendly error page or detailed debug information based on the APP_DEBUG flag.
     * Sets appropriate HTTP status codes (403, 404, 500) and defaults others to 500.
     *
     * @param \Throwable $ex The uncaught exception
     * @return void
     */
    public static function handleException(\Throwable $ex): void
    {
        $debug = Env::get('APP_DEBUG', 'false') === 'true';

        // Use known HTTP status codes or default to 500
        $code = $ex->getCode();
        if (!in_array($code, [403, 404, 500])) {
            $code = 500;
        }

        http_response_code($code);

        $errorMessage = $ex->getMessage();
        $errorDetails = sprintf(
            "Exception: %s\nFile: %s\nLine: %s\n\nTrace:\n%s",
            get_class($ex) . ': ' . $ex->getMessage(),
            $ex->getFile(),
            $ex->getLine(),
            $ex->getTraceAsString()
        );

        // Attempt to use custom error views located at app/Views/errors/{code}.php
        $view = dirname(__DIR__) . "/app/Views/errors/{$code}.php";
        if (file_exists($view)) {
            // Expose variables to the view
            $errorMessage = $errorMessage;
            $errorDetails = $debug ? $errorDetails : '';
            require $view;
        } else {
            // Fallback error display if custom view does not exist
            if ($debug) {
                echo "<h1>Exception</h1><pre>" . htmlspecialchars($errorDetails) . "</pre>";
            } else {
                echo "<h1>{$code} Error</h1><p>Something went wrong.</p>";
            }
        }

        exit;
    }

    /**
     * Handles fatal errors on script shutdown.
     *
     * Detects fatal errors and renders the 500 error page or debug output accordingly.
     *
     * @return void
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null) {
            // Check if the last error was fatal
            if (in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
                $debug = Env::get('APP_DEBUG', 'false') === 'true';

                $message = $error['message'] ?? 'Fatal error';
                $details = sprintf(
                    "Fatal error: %s in %s on line %s",
                    $message,
                    $error['file'] ?? '',
                    $error['line'] ?? ''
                );

                http_response_code(500);
                $view = dirname(__DIR__) . "/app/Views/errors/500.php";

                if (file_exists($view)) {
                    $errorMessage = $message;
                    $errorDetails = $debug ? $details : '';
                    require $view;
                } else {
                    if ($debug) {
                        echo "<pre>" . htmlspecialchars($details) . "</pre>";
                    } else {
                        echo "<h1>500 - Server Error</h1>";
                    }
                }
                exit;
            }
        }
    }
}