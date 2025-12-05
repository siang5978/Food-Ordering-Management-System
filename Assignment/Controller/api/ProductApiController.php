<?php
require_once __DIR__ . '/../../Model/Products.php';
require_once __DIR__ . '/../../FactoryPattern/ProductFactory.php';
class ProductApiController {
    public static function getAll() {
        header('Content-Type: application/json');
        $products = Products::all();
        $data = [];
        foreach ($products as $p) {
        $data[] = [
            "id" => $p->getProductID(),
            "name" => $p->getProductName(),
            "description" => $p->getDescription(),
            "price" => $p->getPrice(),
            "stock" => $p->getStock(),
            "type" => $p->getType(),
            "volume" => $p->getType() === 'Drink' ? $p->getVolume() : null
        ];
    }
        echo json_encode($data);
    }

    public static function getById($params) {
        header('Content-Type: application/json');
        
        $product = Products::find($params['id']);

        if ($product) {
            echo json_encode([
                "id" => $product->getProductID(),
                "name" => $product->getProductName(),
                "description" => $product->getDescription(),
                "price" => $product->getPrice(),
                "stock" => $product->getStock(),
                "type" => $product->getType(),
                "volume" => $product->getType() === 'Drink' ? $product->getVolume() : null
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Product not found"]);
        }
    }

    public static function add() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON request"]);
            return;
        }

        $name = trim($input['name'] ?? '');
        $description = trim($input['description'] ?? '');
        $price = $input['price'] ?? '';
        $stock = $input['stock'] ?? '';
        $type = $input['type'] ?? 'Food';
        $volume = $input['volume'] ?? null;

        $errors = [];

        if (strlen($name) < 3) {
            $errors[] = "Product name must be at least 3 characters.";
        }

        if (strlen($description) < 5) {
            $errors[] = "Description must be at least 5 characters.";
        }

        if (!preg_match("/^[1-9][0-9]*$/", $stock)) {
            $errors[] = "Stock must be a whole number greater than 0.";
        }

        if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $price) || floatval($price) <= 0) {
            $errors[] = "Price must be a positive number with up to 2 decimal places.";
        }

        if ($type === "Drink") {
            if (empty($volume) || !preg_match("/^[1-9][0-9]*$/", $volume)) {
                $errors[] = "Volume must be a whole number greater than 0 when product type is Drink.";
            }
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(["errors" => $errors]);
            return;
        }

        try {
            $row = [
                "ProductID" => null,
                "ProductName" => $name,
                "Description" => $description,
                "Price" => $price,
                "Stock" => $stock,
                "Type" => $type,
                "Volume" => $volume
            ];
            $product = ProductFactory::createFromRow($row);
            $product->insert();

            http_response_code(201);
            echo json_encode([
                "status" => "created",
                "product" => [
                    "id" => $product->getProductID(),
                    "name" => $product->getProductName(),
                    "description" => $product->getDescription(),
                    "price" => $product->getPrice(),
                    "stock" => $product->getStock(),
                    "type" => $product->getType(),
                    "volume" => $product->getType() === 'Drink' ? $product->getVolume() : null
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function update($params) {
        $product = Products::find($params['id']);
        if (!$product) {
            http_response_code(404);
            echo json_encode(["error" => "Product not found"]);
            return;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        
        $id = $input['id'] ?? '';
        $name = $input['name'] ?? '';
        $description = $input['description'] ?? '';
        $stock = $input['stock'] ?? '';
        $price = $input['price'] ?? '';
        $type = $input['type'] ?? '';
        $volume = $input['volume'] ?? null;

        $errors = [];

        if (empty($id) || !ctype_digit($id)) {
            $errors[] = "Invalid product ID.";
        }

        if (strlen($name) < 3) {
            $errors[] = "Product name must be at least 3 characters.";
        }

        if (strlen($description) < 5) {
            $errors[] = "Description must be at least 5 characters.";
        }

        if (!preg_match("/^[1-9][0-9]*$/", $stock)) {
            $errors[] = "Stock must be a whole number greater than 0.";
        }

        if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $price) || floatval($price) <= 0) {
            $errors[] = "Price must be a positive number with up to 2 decimal places.";
        }

        if ($type === "Drink") {
            if (empty($volume) || !preg_match("/^[1-9][0-9]*$/", $volume)) {
                $errors[] = "Volume must be a whole number greater than 0 when product type is Drink.";
            }
        }

        if (!empty($errors)) {
            http_response_code(400); 
            echo json_encode(["errors" => $errors]);
            return;
        }
        
        if (isset($input['name'])) {
            $product->setProductName($input['name']);
        }
        if (isset($input['description'])) {
            $product->setDescription($input['description']);
        }
        if (isset($input['price'])) {
            $product->setPrice($input['price']);
        }
        if (isset($input['stock'])) {
            $product->setStock($input['stock']);
        }
        if (isset($input['type'])) {
            $product->setType($input['type']);
        }
        if (isset($input['volume']) && $input['type'] === 'Drink') {
            $product->setVolume($input['volume']);
        }
        $product->update();
        echo json_encode([
            "status" => "updated",
            "product" => [
                "id" => $product->getProductID(),
                "name" => $product->getProductName(),
                "description" => $product->getDescription(),
                "price" => $product->getPrice(),
                "stock" => $product->getStock(),
                "type" => $product->getType(),
                "volume" => $product->getType() === 'Drink' ? $product->getVolume() : null
            ]
        ]);
    }

    public static function delete($params) {
        header('Content-Type: application/json');

        $product = Products::find($params['id']);
        if (!$product) {
            http_response_code(404);
            echo json_encode(["error" => "Product not found"]);
            return;
        }

        try {
            $product->delete();
            echo json_encode(["status" => "deleted"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    
    public static function salesReport() {
        header('Content-Type: application/json');

        try {
            $productsJson = file_get_contents("http://localhost/Assignment/index.php/api/product");
            $products = $productsJson ? json_decode($productsJson, true) : [];

            $ordersJson = file_get_contents("http://localhost/Assignment/index.php/api/orders");
            $orders = $ordersJson ? json_decode($ordersJson, true) : [];

            $report = [];

            foreach ($products as $product) {
                $productId = $product['id'];
                $unitsSold = 0;
                $totalRevenue = 0;
                $ordersCount = 0;

                foreach ($orders as $order) {
                    if (!isset($order['items']) || !is_array($order['items']))
                        continue;

                    foreach ($order['items'] as $item) {
                        if (isset($item['item']['productID']) && $item['item']['productID'] == $productId) {
                            $unitsSold += $item['item']['quantity'];
                            $totalRevenue += ($item['item']['quantity'] * ($product['price'] ?? 0)); // price从产品JSON获取
                            $ordersCount++;
                        }
                    }
                }

                $report[] = [
                    "productId" => $productId,
                    "productName" => $product['name'],
                    "unitsSold" => $unitsSold,
                    "totalRevenue" => $totalRevenue,
                    "ordersCount" => $ordersCount
                ];
            }

            echo json_encode($report);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Server error: " . $e->getMessage()]);
        }

        exit;
    }
}
