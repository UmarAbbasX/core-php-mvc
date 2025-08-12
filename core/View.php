<?php

namespace Core;

/**
 * View class responsible for rendering PHP view templates.
 */
class View
{
    /**
     * Render a view file with optional data.
     *
     * @param string $view Dot-notated view path (e.g., "users.index")
     * @param array $data Associative array of data to extract into view scope
     * @throws \Exception If view file does not exist
     * @return void
     */
    public static function render(string $view, array $data = []): void
    {
        // Convert dot notation to directory separators
        $viewFile = dirname(__DIR__) . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View [{$view}] not found", 500);
        }

        // Extract data variables for use in the view, skipping if keys conflict
        extract($data, EXTR_SKIP);

        // Include the view file to output content
        require $viewFile;
    }
}