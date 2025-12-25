<?php

namespace App\Config;

class Config {
    private static $envLoaded = false;

    public static function loadEnv() {
        if (self::$envLoaded) {
            return;
        }

        $envPath = dirname(dirname(__DIR__)) . '/.env';
        
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    $value = trim($value, '"\'');
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
        self::$envLoaded = true;
    }

    public static function get($key, $default = null) {
        self::loadEnv();
        return getenv($key) ?: ($_ENV[$key] ?? $default);
    }
}
