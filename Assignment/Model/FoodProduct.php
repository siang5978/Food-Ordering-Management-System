<?php
require_once 'Products.php';

class FoodProduct extends Products {
    #[\Override]
    public function __construct($id, $name, $desc, $price, $stock) {
        parent::__construct($id, $name, $desc, $price, $stock, 'Food', null);
    }
    
    #[\Override]
    public function insert() {
        $db = Db::getInstance();
        $db->execute(
                "INSERT INTO products(ProductName, Description, Price, Stock, Type) VALUES(:name, :desc, :price, :stock, 'Food')",
                [
                    ":name" => $this->ProductName,
                    ":desc" => $this->Description,
                    ":price" => $this->Price,
                    ":stock" => $this->Stock
                ]
        );
    }

    #[\Override]
    public function update() {
        $db = Db::getInstance();
        $db->execute(
                "UPDATE products SET ProductName=:name, Description=:desc, Price=:price, Stock=:stock WHERE ProductID=:id",
                [
                    ":name" => $this->ProductName,
                    ":desc" => $this->Description,
                    ":price" => $this->Price,
                    ":stock" => $this->Stock,
                    ":id" => $this->ProductID
                ]
        );
    }
}

