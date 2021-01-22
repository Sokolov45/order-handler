<?php

class Db
{
    private static $instance;
    public $log = [];


    /**
     * @var \PDO
     */
    public $pdoConnection;

    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function getConnection()
    {
        $driver = DB_DRIVER;
        $server = DB_HOST;
        $user = DB_USER;
        $password = DB_PASSWORD;
        $dbName = DB_NAME;

        if (!isset($this->pdoConnection)) {
            try {
                $this->pdoConnection = new PDO("$driver:host=$server;dbname=$dbName", $user, $password);
                // Определяем ошибки как исключения
                $this->pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e) {
                echo "Подключение не удалось: " . $e->getMessage();
            }
        }
    }

    /*    получить все записи по запросу*/
    public function fetchAll(string $query, $method, array $param = [])
    {
        $startTime = microtime(true);
        $prepared = $this->pdoConnection->prepare($query);
        $res = $prepared->execute();
        if (!$res) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $return = $prepared->fetchAll(PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
        $time = microtime(true) - $startTime;
        $this->log[] = [
            "запрос"=>$query,
            "вызван из метода"=>$method,
            "время запроса"=>$time,
            "количество затронутых строк"=>$affectedRows];
        return $return;
    }

    /*    получить 1 запись по запросу*/
   public function fetchOne(string $query, $method, $param = [])
   {
       $startTime = microtime(true);
       $prepared = $this->pdoConnection->prepare($query);
       $res = $prepared->execute();
       if (!$res) {
           $errorInfo = $prepared->errorInfo();
           trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
           return [];
       }
       $return = $prepared->fetchAll(PDO::FETCH_ASSOC);
       $affectedRows = $prepared->rowCount();
       $time = microtime(true) - $startTime;
       $this->log[] = [
           "запрос"=>$query,
           "вызван из метода"=>$method,
           "время запроса"=>$time,
           "количество затронутых строк"=>$affectedRows];
       return reset($return);
   }

    /*    просто выполнить запрос*/
    public function exec(string $query, $method, array $param = [])
    {
        $startTime = microtime(true);
        $prepared = $this->pdoConnection->prepare($query);
        $res = $prepared->execute();
        if (!$res) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }
        $affectedRows = $prepared->rowCount();
        $time = microtime(true) - $startTime;
        $this->log[] = [
            "запрос"=>$query,
            "вызван из метода"=>$method,
            "время запроса"=>$time,
            "количество затронутых строк"=>$affectedRows];
    }

    public function lastInsertId()
    {
        return $this->pdoConnection->lastInsertId();
    }

    public function getLogs()
    {
        if ($this->log) {
            echo "<pre>";
            var_dump($this->log);
            echo "</pre>";
        }else {
            echo "логов нет.";
        }
    }

}