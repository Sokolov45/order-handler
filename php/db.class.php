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
        }
        return self::$instance;
    }

    private function getConnection()
    {
        $driver = DB_DRIVER;
        $host = DB_HOST;
        $dbName = DB_NAME;
        $user = DB_PASSWORD;
        $password = DB_USER;

        if (!isset($this->pdo)) {
            try {
                $this->pdo = new PDO("$driver:dbname=$dbName;host=$host", $user, $password);
                // Определяем ошибки как исключения
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Подключение прошло успешно!";
            }
            // Отлавливаем исключение
            catch(PDOException $e) {
                echo "Подключение не удалось: " . $e->getMessage();
            }
        }
        return $this->pdo;
    }

    public function fetchAll(string $query, $method, array $parametres = [])
    {
        $startTime = microtime(true);
        $prepared = $this->getConnection()->prepare($query);
        $res = $prepared->execute($parametres);
        if (!$res) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $return = $prepared->fetchAll(PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
        $time = microtime(true) - $startTime;
        $this->log[] = ["Запрос - " => $query, "Время выполнения - " => $time, "Запросом затронуто: " => $affectedRows, "Вызван из метода: " => $method];
        return $return;
    }

    public function fetchOne(string $query, $method, array $parametres = [])
    {
        $startTime = microtime(true);
        $prepared = $this->getConnection()->prepare($query);
        $res = $prepared->execute($parametres);
        if (!$res) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $return = $prepared->fetchAll(PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
//        $time = microtime(true) - $startTime;
        $this->log[] = ["Запрос - " => $query, "Время выполнения - " => microtime(true) - $startTime, "Запросом затронуто: " => $affectedRows, "Вызван из метода: " => $method];
        return reset($return);
    }

    public function exec(string $query, $method, array $parametres = [])
    {
        $startTime = microtime(true);
        $prepared = $this->getConnection()->prepare($query);
        $res = $prepared->execute($parametres);
        if (!$res) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $affectedRows = $prepared->rowCount();
        $time = microtime(true) - $startTime;
        $this->log[] = ["Запрос - " => $query, "Время выполнения - " => $time, "Запросом затронуто: " => $affectedRows, "Вызван из метода: " => $method];
        return $affectedRows;
    }

    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }

    public function getLogs()
    {
        echo "<pre>";
        var_dump($this->log); ;
        echo "</pre>";
    }

}