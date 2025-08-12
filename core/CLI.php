<?php
// Advanced CLI script to manage migrations, seeders, middleware, controllers, and views.
// This script serves as a lightweight artisan-style tool for the Core PHP MVC framework.

declare(strict_types=1);

// Register the autoloader to load classes dynamically
require_once __DIR__ . '/Autoloader.php';
\Core\Autoloader::register();

// Load global helper functions (e.g., base_path, route helpers)
require_once __DIR__ . '/../app/Helpers.php';

use Core\Env;

// Load environment variables from the .env file located at project root
Env::load(dirname(__DIR__) . '/.env');

// Define important directory paths for various application components
$basePath = dirname(__DIR__);
$migrationPath = $basePath . '/database/migrations';
$seederPath = $basePath . '/database/seeders';
$middlewarePath = $basePath . '/app/Middleware';
$controllerPath = $basePath . '/app/Controllers';
$viewPath = $basePath . '/app/views';

// Ensure all necessary directories exist, create them if missing
foreach ([$migrationPath, $seederPath, $middlewarePath, $controllerPath, $viewPath] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

/**
 * Print informational message to console.
 *
 * @param string $msg The message to output.
 */
function info(string $msg): void
{
    echo "[INFO] $msg\n";
}

/**
 * Print error message to console.
 *
 * @param string $msg The error message to output.
 */
function error(string $msg): void
{
    echo "[ERROR] $msg\n";
}

/**
 * Get absolute path from project root.
 *
 * @param string $file Relative file path from project root.
 * @return string Absolute path.
 */
function base_path(string $file = ''): string
{
    return dirname(__DIR__) . '/' . ltrim($file, '/');
}

// Parse CLI arguments and determine the command
$argv = $_SERVER['argv'];
array_shift($argv); // Remove script filename
$cmd = $argv[0] ?? null;

// Handle commands based on the first argument
switch ($cmd) {

    // Generate a new migration file with a timestamped filename and class name
    case 'make:migration':
        $name = $argv[1] ?? null;
        if (!$name) {
            error("Usage: php core/CLI.php make:migration create_users_table");
            exit(1);
        }

        // Timestamp for uniqueness and ordering
        $ts = date('YmdHis');
        $file = $migrationPath . '/' . $ts . '_' . $name . '.php';

        // Class name sanitization and timestamp suffix
        $class = ucfirst(preg_replace('/[^A-Za-z0-9]/', '', $name)) . '_' . $ts;

        // Template stub for migration class with up() and down() methods
        $template = <<<PHP
<?php
/**
 * Migration: {$name}
 * Generated: {$ts}
 */
class {$class}
{
    public function up(\\PDO \$db)
    {
        // Set busy timeout to reduce locking issues during migration
        \$db->exec('PRAGMA busy_timeout = 5000;');

        // Enable foreign keys for referential integrity
        \$db->exec('PRAGMA foreign_keys = ON;');

        // TODO: Write migration 'up' logic here, e.g., creating tables
        /*
        \$db->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );");
        */
    }

    public function down(\\PDO \$db)
    {
        // Prepare database for rollback: disable foreign keys temporarily
        \$db->exec('PRAGMA busy_timeout = 5000;');
        \$db->exec('PRAGMA foreign_keys = OFF;');

        // TODO: Write migration 'down' logic here, e.g., dropping tables
        /*
        \$db->exec('DROP TABLE IF EXISTS users;');
        */

        // Re-enable foreign keys after rollback
        \$db->exec('PRAGMA foreign_keys = ON;');
    }
}
PHP;

        // Save migration file to migrations directory
        file_put_contents($file, $template);
        info("Created migration: {$file}");
        break;

    // Generate a new seeder class file for inserting test or default data
    case 'make:seeder':
        $name = $argv[1] ?? null;
        if (!$name) {
            error("Usage: php core/CLI.php make:seeder UsersTableSeeder");
            exit(1);
        }

        $ts = date('YmdHis');
        $file = $seederPath . '/' . $ts . '_' . $name . '.php';
        $class = ucfirst(preg_replace('/[^A-Za-z0-9]/', '', $name)) . '_' . $ts;

        $template = <<<PHP
<?php
/**
 * Seeder: {$name}
 * Generated: {$ts}
 */
class {$class}
{
    public function run(\\PDO \$db)
    {
        // TODO: Insert seed data here
        // Example:
        // \$db->exec("INSERT INTO users (name,email) VALUES ('John','john@example.com');");
    }
}
PHP;

        file_put_contents($file, $template);
        info("Created seeder: {$file}");
        break;

    // Generate middleware stub with basic handle method accepting request and next callable
    case 'make:middleware':
        $name = $argv[1] ?? null;
        if (!$name) {
            error("Usage: php core/CLI.php make:middleware AuthMiddleware");
            exit(1);
        }

        $class = ucfirst(preg_replace('/[^A-Za-z0-9]/', '', $name));
        $file = $middlewarePath . '/' . $class . '.php';

        $template = <<<PHP
<?php
namespace App\Middleware;

/**
 * Class {$class}
 *
 * Middleware to process HTTP requests.
 */
class {$class}
{
    /**
     * Handle the incoming request.
     *
     * @param mixed \$request The HTTP request object.
     * @param callable \$next Callback to invoke next middleware or controller.
     * @return mixed
     */
    public function handle(\$request, \$next)
    {
        // TODO: Add middleware logic before passing to next middleware/controller
        // Example: authentication, logging, etc.

        return \$next(\$request);
    }
}
PHP;

        file_put_contents($file, $template);
        info("Created middleware: {$file}");
        break;

    // Generate controller stub with RESTful action method placeholders
    case 'make:controller':
        $name = $argv[1] ?? null;
        if (!$name) {
            error("Usage: php core/CLI.php make:controller UserController");
            exit(1);
        }

        $class = ucfirst(preg_replace('/[^A-Za-z0-9]/', '', $name));
        $file = $controllerPath . '/' . $class . '.php';

        $template = <<<PHP
<?php
namespace App\Controllers;

/**
 * Class {$class}
 *
 * Controller with RESTful resource actions.
 */
class {$class}
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: List resources
    }

    /**
     * Display the specified resource.
     *
     * @param mixed \$id Identifier of the resource.
     */
    public function show(\$id)
    {
        // TODO: Show single resource by id
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Show create form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // TODO: Save new resource
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed \$id Identifier of the resource.
     */
    public function edit(\$id)
    {
        // TODO: Show edit form
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed \$id Identifier of the resource.
     */
    public function update(\$id)
    {
        // TODO: Update resource
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed \$id Identifier of the resource.
     */
    public function destroy(\$id)
    {
        // TODO: Delete resource
    }
}
PHP;

        file_put_contents($file, $template);
        info("Created controller: {$file}");
        break;

    // Generate a basic HTML view file with a title and welcome message
    case 'make:view':
        $name = $argv[1] ?? null;
        if (!$name) {
            error("Usage: php core/CLI.php make:view home");
            exit(1);
        }

        // Sanitize view filename
        $filename = strtolower(preg_replace('/[^a-z0-9_\-]/i', '', $name)) . '.php';
        $file = $viewPath . '/' . $filename;

        $template = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$name} View</title>
</head>
<body>
    <h1>This is the {$name} view</h1>
    <p>Welcome to your new view!</p>
</body>
</html>
HTML;

        file_put_contents($file, $template);
        info("Created view: {$file}");
        break;

    // Run all pending migrations by loading migration files and invoking their up() method
    case 'migrate':
    case 'migrate:run':
        $dbFile = base_path('database/database.sqlite');

        // Create SQLite DB file if it doesn't exist
        if (!file_exists($dbFile)) {
            info("SQLite DB file not found. Creating new one at {$dbFile}");
            touch($dbFile);
        }

        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA busy_timeout = 5000;');

        // Create the migrations tracking table if missing
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration TEXT NOT NULL,
            migrated_at TEXT NOT NULL
        )");

        // Retrieve already applied migrations
        $applied = [];
        $stmt = $pdo->query("SELECT migration FROM migrations");
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $m) {
            $applied[$m] = true;
        }

        // Find all migration files sorted by filename (timestamp)
        $files = glob($migrationPath . '/*.php');
        sort($files);

        // Execute migrations not yet applied
        foreach ($files as $file) {
            $name = basename($file);

            if (isset($applied[$name])) {
                info("Skipping (already migrated): {$name}");
                continue;
            }

            // Load migration class dynamically
            $classesBefore = get_declared_classes();
            require_once $file;
            $classesAfter = get_declared_classes();
            $newClasses = array_diff($classesAfter, $classesBefore);
            $className = end($newClasses);

            $inst = new $className();

            info("Migrating: {$name} ...");
            $inst->up($pdo);

            // Record migration in migrations table
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, migrated_at) VALUES (:m, :t)");
            $stmt->execute([':m' => $name, ':t' => date('Y-m-d H:i:s')]);

            info("Migrated: {$name}");
        }
        break;

    // Rollback the last applied migration by invoking its down() method and deleting its record
    case 'migrate:rollback':
        $pdo = new PDO('sqlite:' . base_path('database/database.sqlite'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA busy_timeout = 5000;');

        // Fetch the latest migration applied
        $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch(PDO::FETCH_COLUMN);

        if (!$last) {
            info("No migrations to rollback.");
            exit;
        }

        $file = $migrationPath . '/' . $last;
        if (!file_exists($file)) {
            error("Migration file not found: {$file}");
            exit(1);
        }

        try {
            // Load the migration class
            $classesBefore = get_declared_classes();
            require_once $file;
            $classesAfter = get_declared_classes();
            $newClasses = array_diff($classesAfter, $classesBefore);
            $className = end($newClasses);

            $inst = new $className();

            info("Rolling back: {$last} ...");
            $inst->down($pdo);

            // Remove migration record
            $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = :m");
            $stmt->execute([':m' => $last]);

            info("Rolled back: {$last}");
        } catch (PDOException $e) {
            error("Rollback failed due to DB error: " . $e->getMessage());
            exit(1);
        }
        break;

    // Display status of all applied migrations
    case 'migrate:status':
        $dbFile = base_path('database/database.sqlite');

        if (!file_exists($dbFile)) {
            info("No database file found.");
            exit;
        }

        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA busy_timeout = 5000;');

        $stmt = $pdo->query("SELECT migration, migrated_at FROM migrations ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            info("No migrations applied yet.");
            exit;
        }

        foreach ($rows as $row) {
            echo $row['migration'] . " -> " . $row['migrated_at'] . PHP_EOL;
        }
        break;

    // Run all database seeders to populate test or default data
    case 'db:seed':
    case 'seed':
        $dbFile = base_path('database/database.sqlite');

        if (!file_exists($dbFile)) {
            error("No database found, run migrate first.");
            exit(1);
        }

        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA busy_timeout = 5000;');

        $files = glob($seederPath . '/*.php');
        sort($files);

        // Execute each seeder's run() method
        foreach ($files as $file) {
            $classesBefore = get_declared_classes();
            require_once $file;
            $classesAfter = get_declared_classes();
            $newClasses = array_diff($classesAfter, $classesBefore);
            $className = end($newClasses);

            $inst = new $className();

            info("Seeding: " . basename($file));
            $inst->run($pdo);
            info("Seeded: " . basename($file));
        }
        break;

    // Show usage instructions for the CLI tool if no or unknown command is given
    default:
        echo <<<TXT
Usage:
 php core/CLI.php make:migration <name>      # Create a new migration file
 php core/CLI.php make:seeder <name>         # Create a new seeder class
 php core/CLI.php make:middleware <name>     # Create a new middleware class
 php core/CLI.php make:controller <name>     # Create a new controller class
 php core/CLI.php make:view <name>           # Create a new view file
 php core/CLI.php migrate                     # Run all pending migrations
 php core/CLI.php migrate:rollback            # Rollback last migration
 php core/CLI.php migrate:status             # Show migration status
 php core/CLI.php db:seed                     # Run all seeders

TXT;
}
