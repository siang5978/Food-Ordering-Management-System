<?php

class Cart {

    private $CartID;
    private $UserID;
    private $ProductID;
    private $Quantity;

    public function __construct($CartID, $UserID, $ProductID, $Quantity) {
        $this->CartID = $CartID;
        $this->UserID = $UserID;
        $this->ProductID = $ProductID;
        $this->Quantity = $Quantity;
    }

    //Getter
    public function getCartID() {
        return $this->CartID;
    }

    public function getUserID() {
        return $this->UserID;
    }

    public function getProductID() {
        return $this->ProductID;
    }

    public function getQuantity() {
        return $this->Quantity;
    }

    //Setter
    public function setCartID($CartID) {
        $this->CartID = $CartID;
    }

    public function setUserID($UserID) {
        $this->UserID = $UserID;
    }

    public function setProductID($ProductID) {
        $this->ProductID = $ProductID;
    }

    public function setQuantity($Quantity) {
        $this->Quantity = $Quantity;
    }

    //ORM Methods
    public static function findByUser($UserID) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM cart WHERE UserID = ?", [$UserID]);
        return array_map(fn($row) => new self($row['CartID'], $row['UserID'], $row['ProductID'], $row['Quantity']), $rows);
    }

    public function insert() {
        $db = Db::getInstance();

        $rows = $db->query(
                "SELECT * FROM cart WHERE UserID = ? AND ProductID = ? LIMIT 1",
                [$this->UserID, $this->ProductID]
        );

        if (!empty($rows)) {
            $existing = $rows[0];
            $this->CartID = $existing['CartID'];
            $this->Quantity += $existing['Quantity'];
            $db->query(
                    "UPDATE cart SET Quantity = ? WHERE CartID = ?",
                    [$this->Quantity, $this->CartID]
            );
        } else {
            $db->query(
                    "INSERT INTO cart (UserID, ProductID, Quantity) VALUES (?, ?, ?)",
                    [$this->UserID, $this->ProductID, $this->Quantity]
            );
        }
    }
    
    public function update() {
        $db = Db::getInstance();
        $db->query(
                "UPDATE cart SET Quantity = ? WHERE CartID = ?",
                [$this->Quantity, $this->CartID]
        );
    }

    public function delete() {
        $db = Db::getInstance();
        $db->query("DELETE FROM cart WHERE CartID = ?", [$this->CartID]);
        $this->CartID = null;
    }
}
