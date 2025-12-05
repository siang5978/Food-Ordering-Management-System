<?php

class Order_Items {

    private $OrderItemID;
    private $OrderID;
    private $ProductID;
    private $Quantity;
    private $Price;

    public function __construct($OrderItemID = null, $OrderID = null, $ProductID = null, $Quantity = null, $Price = null) {
        $this->OrderItemID = $OrderItemID;
        $this->OrderID = $OrderID;
        $this->ProductID = $ProductID;
        $this->Quantity = $Quantity;
        $this->Price = $Price;
    }

    //Getter
    public function getOrderItemID() {
        return $this->OrderItemID;
    }

    public function getOrderID() {
        return $this->OrderID;
    }

    public function getProductID() {
        return $this->ProductID;
    }

    public function getQuantity() {
        return $this->Quantity;
    }

    public function getPrice() {
        return $this->Price;
    }

    //Setter
    public function setOrderItemID($OrderItemID) {
        $this->OrderItemID = $OrderItemID;
    }

    public function setOrderID($OrderID) {
        $this->OrderID = $OrderID;
    }

    public function setProductID($ProductID) {
        $this->ProductID = $ProductID;
    }

    public function setQuantity($Quantity) {
        $this->Quantity = $Quantity;
    }

    public function setPrice($Price) {
        $this->Price = $Price;
    }

    //ORM Methods
    public static function findByOrder($OrderID) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM order_items WHERE OrderID = ?", [$OrderID]);

        return array_map(fn($row) => new self(
                        $row['OrderItemID'],
                        $row['OrderID'],
                        $row['ProductID'],
                        $row['Quantity'],
                        $row['Price']
                ), $rows);
    }

    public function insert() {
        $db = Db::getInstance();
        $db->query(
                "INSERT INTO order_items (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)",
                [$this->OrderID, $this->ProductID, $this->Quantity, $this->Price]
        );
        $this->OrderItemID = $db->lastInsertId();
    }

    public function update() {
        if (!$this->OrderItemID) {
            throw new Exception("Order item not saved in DB.");
        }
        $db = Db::getInstance();
        $db->query(
                "UPDATE order_items SET OrderID = ?, ProductID = ?, Quantity = ?, Price = ? WHERE OrderItemID = ?",
                [$this->OrderID, $this->ProductID, $this->Quantity, $this->Price, $this->OrderItemID]
        );
    }

    public static function deleteByOrder($OrderID) {
        $db = Db::getInstance();
        $db->query("DELETE FROM order_items WHERE OrderID = ?", [$OrderID]);
    }
}
