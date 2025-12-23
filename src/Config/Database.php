<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $db_name = 'student_tracker';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Check for environment variables (useful for production vs local)
            $host = getenv('DB_HOST') ?: $this->host;
            $db_name = getenv('DB_NAME') ?: $this->db_name;
            $username = getenv('DB_USER') ?: $this->username;
            $password = getenv('DB_PASS') ?: $this->password;

            $this->conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // In production, log this, don't echo it
            error_log("Connection error: " . $exception->getMessage());
            die("Database connection failed. Please check logs.");
        }

        return $this->conn;
    }
}
