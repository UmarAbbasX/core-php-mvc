<?php

namespace Core;

/**
 * Class Env
 *
 * Simple environment variable loader and accessor.
 * This class reads a `.env` file and loads its key-value pairs into the environment,
 * making configuration values available via superglobals and getenv().
 *
 * It supports comments, empty lines, and quoted values in the .env file.
 */
class Env
{
    /**
     * Load environment variables from a given file path.
     *
     * This method reads the specified .env file line by line, parsing key=value pairs.
     * It skips empty lines and comments (lines starting with '#').
     * Quotes around values (single or double) are removed.
     * The loaded variables are injected into $_ENV, $_SERVER, and via putenv().
     *
     * @param string $path Absolute or relative path to the .env file
     * @return void
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            // No .env file found, silently skip loading
            return;
        }

        // Read the file ignoring empty lines
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and invalid lines without '='
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            // Split into name and value at first '='
            [$name, $value] = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value);

            // Remove surrounding quotes (single or double)
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            // Set environment variables in superglobals and system environment
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv("$name=$value");
        }
    }

    /**
     * Retrieve an environment variable by key with optional default fallback.
     *
     * This method attempts to return the value from $_ENV, $_SERVER, or getenv(),
     * in that order. If none exist, it returns the provided default value.
     *
     * @param string $key The environment variable name
     * @param mixed|null $default Value to return if variable is not set
     * @return mixed|null The environment variable value or default
     */
    public static function get(string $key, $default = null)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        return $default;
    }
}
