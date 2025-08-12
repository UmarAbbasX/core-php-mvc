<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    // Name of the database table
    protected static string $table = 'users';

    /**
     * Find a user by email
     */
    public static function findByEmail(string $email): ?array
    {
        return static::fetch("SELECT * FROM " . static::$table . " WHERE email = :email", [
            'email' => $email,
        ]);
    }

    /**
     * Check if a user exists by email
     */
    public static function exists(string $email): bool
    {
        $result = static::fetch("SELECT id FROM " . static::$table . " WHERE email = :email", [
            'email' => $email,
        ]);
        return $result !== false;
    }

    /**
     * Create a new user
     */
    public static function create(array $data): bool
    {
        return static::insert(static::$table, $data);
    }

    /**
     * Find a user by ID
     */
    public static function find(int $id): ?array
    {
        return static::fetch("SELECT * FROM " . static::$table . " WHERE id = :id", [
            'id' => $id,
        ]);
    }

    /**
     * Return all users (optional)
     */
    public static function all(): array
    {
        return static::fetchAll("SELECT * FROM " . static::$table);
    }
}
