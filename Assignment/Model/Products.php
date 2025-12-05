<?php

abstract class Products {
    protected $ProductID;
    protected $ProductName;
    protected $Description;
    protected $Price;
    protected $Stock;
    protected $Type;

    public function __construct($ProductID, $ProductName, $Description, $Price, $Stock, $Type = 'Food') {
        $this->ProductID = $ProductID;
        $this->ProductName = $ProductName;
        $this->Description = $Description;
        $this->Price = $Price;
        $this->Stock = $Stock;
        $this->Type = $Type;
    }

    //Getter
    public function getProductID() {
        return $this->ProductID;
    }

    public function getProductName() {
        return $this->ProductName;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function getPrice() {
        return $this->Price;
    }

    public function getStock() {
        return $this->Stock;
    }
    
    public function getType() {
        return $this->Type;
    }

    //Setter
    public function setProductID($id) {
        $this->ProductID = $id;
    }

    public function setProductName($name) {
        $this->ProductName = $name;
    }

    public function setDescription($desc) {
        $this->Description = $desc;
    }

    public function setPrice($price) {
        $this->Price = $price;
    }

    public function setStock($stock) {
        $this->Stock = $stock;
    }

    public function setType($type) {
        $this->Type = $type;
    }

    //ORM Methods
    abstract public function insert();
    abstract public function update();
    
    public static function all() {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM products");
        return array_map(fn($row) => ProductFactory::createFromRow($row), $rows);
    }

    public static function find($id) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM products WHERE ProductID = :id", [":id" => $id]);
        if (!$rows){
            return null;
        }
        return ProductFactory::createFromRow($rows[0]);
    }
    
    public static function filterByType($type) {
        $db = Db::getInstance();
        $rows = $db->query("SELECT * FROM products WHERE Type = :type", [":type" => $type]);
        return array_map(fn($row) => ProductFactory::createFromRow($row), $rows);
    }

    public function delete() {
        $db = Db::getInstance();
        $db->execute("DELETE FROM products WHERE ProductID = :id", [":id" => $this->ProductID]);
    }
}
