<?php

class DB {
    private static $connection;

    public static function getConnection() {
        if (!self::$connection) {
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');

            try {
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(json_encode(['error' => $e->getMessage()]));
            }
        }

        return self::$connection;
    }
}
