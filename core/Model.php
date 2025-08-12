<?php

namespace Core;

use PDO;

/**
 * Base Model class providing common database operations
 *
 * This class manages the PDO connection and provides static methods
 * for executing queries, fetching data, and modifying records.
 */
class Model
{
    /**
     * @var PDO|null The PDO database connection instance
     */
    protected static ?PDO $pdo = null;

    /**
     * Returns the PDO connection instance.
     *
     * Uses environment variables to determine the database driver and credentials.
     * Supports SQLite and MySQL.
     *
     * @return PDO The PDO connection
     * @throws \Exception If an unsupported driver is used
     */
    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $driver = getenv('DB_DRIVER') ?: 'sqlite';

        try {
            if ($driver === 'sqlite') {
                $dbPath = base_path(getenv('DB_DATABASE') ?: 'database/database.sqlite');
                self::$pdo = new PDO("sqlite:" . $dbPath);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } elseif ($driver === 'mysql') {
                $host = getenv('DB_HOST') ?: '127.0.0.1';
                $dbname = getenv('DB_NAME') ?: 'test';
                $user = getenv('DB_USER') ?: 'root';
                $pass = getenv('DB_PASS') ?: '';
                self::$pdo = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } else {
                throw new \Exception("Unsupported database driver: {$driver}");
            }

            return self::$pdo;
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Prepare and execute a raw SQL query with parameters.
     *
     * @param string $sql The SQL query string
     * @param array $params Parameters for prepared statement
     * @return \PDOStatement The executed statement
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = static::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all results from a query as an associative array.
     *
     * @param string $sql The SQL query string
     * @param array $params Parameters for prepared statement
     * @return array The result set as an array of associative arrays
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return static::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a single result row from a query.
     *
     * @param string $sql The SQL query string
     * @param array $params Parameters for prepared statement
     * @return array|null The single result row as an associative array, or null if none
     */
    public static function fetch(string $sql, array $params = []): ?array
    {
        $result = static::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }

    /**
     * Insert a new record into a table.
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs
     * @return string Last inserted ID
     */
    public static function insert(string $table, array $data): string
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);
        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }
        static::query($sql, $params);
        return static::connection()->lastInsertId();
    }

    /**
     * Update records in a table.
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value to update
     * @param string $where WHERE clause (without 'WHERE')
     * @param array $whereParams Parameters for the WHERE clause
     * @return \PDOStatement
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): \PDOStatement
    {
        $sets = [];
        $params = [];
        foreach ($data as $column => $value) {
            $sets[] = "{$column} = :set_{$column}";
            $params[":set_{$column}"] = $value;
        }

        $params = array_merge($params, $whereParams);
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE {$where}";

        return static::query($sql, $params);
    }

    /**
     * Delete records from a table.
     *
     * @param string $table Table name
     * @param string $where WHERE clause (without 'WHERE')
     * @param array $whereParams Parameters for the WHERE clause
     * @return \PDOStatement
     */
    public static function delete(string $table, string $where, array $whereParams = []): \PDOStatement
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return static::query($sql, $whereParams);
    }

    /**
     * Check if a table exists in the current database.
     *
     * Supports SQLite and MySQL.
     *
     * @param string $table Table name to check
     * @return bool True if table exists, false otherwise
     */
    public static function tableExists(string $table): bool
    {
        $conn = static::connection();
        $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $result = static::fetch(
                "SELECT name FROM sqlite_master WHERE type='table' AND name = :name",
                ['name' => $table]
            );
            return (bool)$result;
        } elseif ($driver === 'mysql') {
            $db = Env::get('DB_DATABASE');
            $result = static::fetch(
                "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :name",
                ['db' => $db, 'name' => $table]
            );
            return (bool)$result;
        }

        return false;
    }
}
