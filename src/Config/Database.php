<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        // Load from environment variables with local defaults
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'enviroapps_student_tracker';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: null;
    }

    public function getConnection() {
        // Singleton pattern: return existing connection if already established
        if ($this->conn) {
            return $this->conn;
        }

        try {
            // Build DSN - only add port if explicitly set (for shared hosting compatibility)
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            if ($this->port) {
                $dsn .= ";port={$this->port}";
            }
            
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

        } catch (PDOException $exception) {
            // In production, log the error instead of displaying it
            if (getenv('APP_ENV') === 'production') {
                error_log("Database Error: " . $exception->getMessage());
                die("Database connection failed. Please try again later.");
            } else {
                die("Database Error: " . $exception->getMessage());
            }
        }

        return $this->conn;
    }
}
