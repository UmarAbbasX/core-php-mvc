<?php

namespace Core;

/**
 * Class Autoloader
 *
 * Registers an autoloader function that automatically loads PHP classes
 * from the Core and App namespaces based on their fully qualified class names.
 *
 * This implementation follows PSR-4 style conventions for namespace to file path mapping.
 */
class Autoloader
{
    /**
     * Registers the autoload function with SPL autoload stack.
     *
     * The registered callback will:
     * - Strip leading backslashes from the class name.
     * - Match the namespace prefix (Core or App).
     * - Convert the namespace to the appropriate base directory.
     * - Convert namespace separators to directory separators.
     * - Require the corresponding PHP file if it exists.
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            // Remove leading backslash from class name, if present
            $class = ltrim($class, '\\');

            // Map namespaces to their base directories
            $prefixes = [
                'Core\\' => __DIR__ . '/',
                'App\\'  => dirname(__DIR__) . '/app/',
            ];

            // Iterate over prefixes to find matching namespace
            foreach ($prefixes as $namespacePrefix => $baseDir) {
                // Check if the class uses the current namespace prefix
                if (str_starts_with($class, $namespacePrefix)) {
                    // Get the relative class name after the namespace prefix
                    $relativeClass = substr($class, strlen($namespacePrefix));

                    // Replace namespace separators with directory separators
                    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                    // Require the class file if it exists
                    if (file_exists($file)) {
                        require_once $file;
                    }

                    // Stop processing after first successful match
                    return;
                }
            }
            // If no matching namespace prefix is found, do nothing (allow other autoloaders to handle)
        });
    }
}
