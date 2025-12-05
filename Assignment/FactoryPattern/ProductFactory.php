<?php
require_once __DIR__ . '/../Model/FoodProduct.php';
require_once __DIR__ . '/../Model/DrinkProduct.php';

class ProductFactory {
    public static function createFromRow($row) {
        if ($row['Type'] === 'Drink') {
            return new DrinkProduct(
                    $row['ProductID'],
                    $row['ProductName'],
                    $row['Description'],
                    $row['Price'],
                    $row['Stock'],
                    $row['Volume']
            );
        } else {
            return new FoodProduct(
                    $row['ProductID'],
                    $row['ProductName'],
                    $row['Description'],
                    $row['Price'],
                    $row['Stock']
            );
        }
    }
}
