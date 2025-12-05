<?php

class Orders {

    private $OrderID;
    private $UserID;
    private $OrderDate;
    private $TotalAmount;
    private $PaymentStatus;

    public function __construct($OrderID, $UserID, $OrderDate, $TotalAmount, $PaymentStatus) {
        $this->OrderID = $OrderID;
        $this->UserID = $UserID;
        $this->OrderDate = $OrderDate;
        $this->TotalAmount = $TotalAmount;
        $this->PaymentStatus = $PaymentStatus;
    }

    //Getter
    public function getOrderID() {
        return $this->OrderID;
    }

    public function getUserID() {
        return $this->UserID;
    }

    public function getOrderDate() {
        return $this->OrderDate;
    }

    public function getTotalAmount() {
        return $this->TotalAmount;
    }

    public function getPaymentStatus() {
        return $this->PaymentStatus;
    }

    //Setter
    public function setOrderID($OrderID) {
        $this->OrderID = $OrderID;
    }

    public function setUserID($UserID) {
        $this->UserID = $UserID;
    }

    public function setOrderDate($OrderDate) {
        $this->OrderDate = $OrderDate;
    }

    public function setTotalAmount($TotalAmount) {
        $this->TotalAmount = $TotalAmount;
    }

    public function setPaymentStatus($PaymentStatus) {
        $this->PaymentStatus = $PaymentStatus;
    }

    // ORM Methods
    public static function all() {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM orders");
        return array_map(fn($row) => new self(
                        $row['OrderID'],
                        $row['UserID'],
                        $row['OrderDate'],
                        $row['TotalAmount'],
                        $row['PaymentStatus']
                ), $rows);
    }

    public static function findByUser($UserID) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM orders WHERE UserID = ?", [$UserID]);
        return array_map(fn($row) => new self(
                        $row['OrderID'],
                        $row['UserID'],
                        $row['OrderDate'],
                        $row['TotalAmount'],
                        $row['PaymentStatus']
                ), $rows);
    }

    public static function findByID($OrderID) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM orders WHERE OrderID = ?", [$OrderID]);
        if (empty($rows)){
            return null;
        }
        
        $row = $rows[0];
        return new self(
                $row['OrderID'],
                $row['UserID'],
                $row['OrderDate'],
                $row['TotalAmount'],
                $row['PaymentStatus']
        );
    }

    public function insert() {
        $db = Db::getInstance();
        $db->query(
                "INSERT INTO orders (UserID, OrderDate, TotalAmount, PaymentStatus) VALUES (?, NOW(), ?, ?)",
                [$this->UserID, $this->TotalAmount, $this->PaymentStatus]
        );
        $this->OrderID = $db->lastInsertId();
    }

    public function update() {
        if (!$this->OrderID) {
            throw new Exception("Order not saved in DB.");
        }
        $db = Db::getInstance();
        $db->query(
                "UPDATE orders SET UserID = ?, TotalAmount = ?, PaymentStatus = ? WHERE OrderID = ?",
                [$this->UserID, $this->TotalAmount, $this->PaymentStatus, $this->OrderID]
        );
    }

    public function delete() {
        if (!$this->OrderID) {
            throw new Exception("Order not saved in DB.");
        }
        Order_Items::deleteByOrder($this->OrderID);

        $db = Db::getInstance();
        $db->query("DELETE FROM orders WHERE OrderID = ?", [$this->OrderID]);
        $this->OrderID = null;
    }

    public function updateStatus($PaymentStatus) {
        $this->PaymentStatus = $PaymentStatus;
        $this->update();
    }
}
