<?php

/**
 * Migration: create_users_table
 * Generated: 2025-08-09 10:14:05
 */

class CreateUsersTable_20250809101405
{
    public function up(\PDO $db): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $db->exec($sql);
    }

    public function down(\PDO $db): void
    {
        $db->exec('PRAGMA foreign_keys = OFF;');
        $db->exec('DROP TABLE IF EXISTS users;');
        $db->exec('PRAGMA foreign_keys = ON;');
    }
}
