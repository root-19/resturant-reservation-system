<?php


define('DB_HOST', 'localhost');
define('DB_NAME', 'resturant');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Could not connect to the database: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    public static function close() {
        self::$pdo = null;
    }
}
