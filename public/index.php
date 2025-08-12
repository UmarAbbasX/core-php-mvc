<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../core/Autoloader.php';
\Core\Autoloader::register();

// Load helpers (csrf, route() helper, etc.)
require_once __DIR__ . '/../app/Helpers.php';

use Core\Env;
use Core\ErrorHandler;
use Core\App;

// Load .env (optional)
Env::load(dirname(__DIR__) . '/.env');

// Register global error handler
ErrorHandler::register();

// Run the app
$app = new App();
$app->run();
