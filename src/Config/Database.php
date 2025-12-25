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
    private static $instance = null;

    public function __construct() {
        // Load .env file if it exists
        $this->loadEnv();
        
        // Load from environment variables with local defaults
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'enviroapps_student_tracker';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: null;
    }

    private function loadEnv() {
        // Try to find .env file in project root
        $envPath = dirname(dirname(__DIR__)) . '/.env';
        
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Skip comments
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                
                // Parse KEY=VALUE
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    
                    // Remove quotes if present
                    $value = trim($value, '"\'');
                    
                    // Set as environment variable
                    putenv("$key=$value");
                }
            }
        }
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
            
            // PDO options
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            // Add SSL for TiDB Cloud (or any host requiring SSL)
            $useSSL = getenv('DB_SSL') ?: 'false';
            if ($useSSL === 'true' || $useSSL === '1') {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
                // Use system CA bundle - works on most Linux systems
                $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
            }
            
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                $options
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
