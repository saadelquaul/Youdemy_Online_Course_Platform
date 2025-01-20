<?php

class Database {
    private static $instance = null;

    private function __construct() {
        try {
            self::$instance = new PDO('mysql:host=localhost;dbname=youdemy', 'root','123456');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            new self();
        }
        return self::$instance;
    }

    
    
}


?>