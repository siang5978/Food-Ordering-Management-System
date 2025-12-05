<?php
require_once 'Products.php';

class DrinkProduct extends Products {
    private $Volume;
    
    public function getVolume() {
        return $this->Volume;
    }
    
    public function setVolume($volume) {
        $this->Volume = $volume;
    }
    
    #[\Override]
    public function __construct($id, $name, $desc, $price, $stock, $volume) {
        parent::__construct($id, $name, $desc, $price, $stock, 'Drink', $volume);
        $this->Volume = $volume;
    }
    
    #[\Override]
    public function insert() {
        $db = Db::getInstance();
        $db->execute(
                "INSERT INTO products(ProductName, Description, Price, Stock, Type, Volume)
             VALUES(:name, :desc, :price, :stock, 'Drink', :volume)",
                [
                    ":name" => $this->ProductName,
                    ":desc" => $this->Description,
                    ":price" => $this->Price,
                    ":stock" => $this->Stock,
                    ":volume" => $this->Volume
                ]
        );
    }

    #[\Override]
    public function update() {
        $db = Db::getInstance();
        $db->execute(
                "UPDATE products SET ProductName=:name, Description=:desc, Price=:price, Stock=:stock, Volume=:volume
             WHERE ProductID=:id",
                [
                    ":name" => $this->ProductName,
                    ":desc" => $this->Description,
                    ":price" => $this->Price,
                    ":stock" => $this->Stock,
                    ":volume" => $this->Volume,
                    ":id" => $this->ProductID
                ]
        );
    }
}

