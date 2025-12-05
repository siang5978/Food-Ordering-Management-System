<?php
class Db{
    private static $instance = null;
    private $pdo;

    private function __construct($host, $user, $pass, $dbname) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION];
        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public static function init($host, $user, $pass, $dbname) {
        if (self::$instance === null) {
            self::$instance = new self($host, $user, $pass, $dbname);
        }
        return self::$instance;
    }

    public static function getInstance() {
        return self::$instance;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
