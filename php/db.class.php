<?php

class Db
{
    private static $instance;
    private $pdo;
    private $log = [];

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            return self::$instance;
        }
    }

    public function getConnection()
    {
        $driver = DB_DRIVER;
        $dbName = DB_HOST;
        $host = DB_NAME;
        $user = DB_PASSWORD;
        $password = DB_USER;

        if (!isset($this->pdo)) {
            $this->pdo = new PDO("$driver:dbname=$dbName;host=$host", $user, $password);
            return $this->pdo;
        }
    }

    public function fetchAll($query, $method, $parametres)
    {
        $startTime = microtime(true);
        $prepared = $this->getConnection()->prepare($query);
        $res = $prepared->execute($parametres);
        if (!$res) {
            echo "Извлечение из базы данных не удалось";
        }


    }
}