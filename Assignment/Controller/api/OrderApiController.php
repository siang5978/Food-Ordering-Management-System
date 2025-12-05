<?php
class OrderApiController {

    public static function getAll() {
        header('Content-Type: application/json');

        $orders = Orders::all();
        $history = [];

        foreach ($orders as $order) {
            $orderItems = Order_Items::findByOrder($order->getOrderID());
            $itemsWithProduct = [];

            foreach ($orderItems as $item) {
                $productId = $item->getProductID();

                // Consume Product API
                $productJson = file_get_contents("http://localhost/Assignment/index.php/api/product/$productId");
                $productData = $productJson ? json_decode($productJson, true) : ["name" => "Unknown Product"];

                $itemsWithProduct[] = [
                    'item' => [
                        'id' => $item->getOrderItemID(),
                        'productID' => $productId,
                        'quantity' => $item->getQuantity()
                    ],
                    'productName' => $productData['name'] ?? 'Unknown Product'
                ];
            }

            $history[] = [
                'order' => [
                    'id' => $order->getOrderID(),
                    'userId' => $order->getUserID(),
                    'orderDate' => $order->getOrderDate(),
                    'totalAmount' => $order->getTotalAmount(),
                    'paymentStatus' => $order->getPaymentStatus()
                ],
                'items' => $itemsWithProduct,
            ];
        }

        echo json_encode($history);
        exit;
        }
    
    public static function getById($params) {
        header('Content-Type: application/json');
        $order = Orders::findByID($params['id']);
        if (!$order) {
            http_response_code(404);
            echo json_encode(["error" => "Order not found"]);
            exit;
        }

        $orderItems = Order_Items::findByOrder($order->getOrderID());
        $items = [];

        foreach ($orderItems as $item) {
            $productId = $item->getProductID();
            
            //Consume Product Api
            $productJson = file_get_contents("http://localhost/Assignment/index.php/api/product/$productId");
            $productData = $productJson ? json_decode($productJson, true) : ["name" => "Unknown Product"];

            $items[] = [
                "id" => $item->getOrderItemID(),
                "quantity" => $item->getQuantity(),
                "price" => $item->getPrice(),
                "productName" => $productData['name'] ?? 'Unknown Product'
            ];
        }

        echo json_encode([
            "order" => [
                "id" => $order->getOrderID(),
                "date" => $order->getOrderDate(),
                "total" => $order->getTotalAmount(),
                "paymentStatus" => $order->getPaymentStatus(),
                "items" => $items
            ]
        ]);
        exit;
    }

    public static function update($params) {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);

        $order = Orders::findByID($params['id']);
        if (!$order) {
            http_response_code(404);
            echo json_encode(["error" => "Order not found"]);
            exit;
        }

        if (!isset($input['paymentStatus']) || !in_array($input['paymentStatus'], ["Pending", "Paid", "Failed", "Refunded"])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid payment status"]);
            exit;
        }

        $order->setPaymentStatus($input['paymentStatus']);
        $order->update();

        echo json_encode([
            "status" => "updated",
            "order" => [
                "id" => $order->getOrderID(),
                "paymentStatus" => $order->getPaymentStatus()
            ]
        ]);
        exit;
    }

    public static function delete($params) {
        header('Content-Type: application/json');
        $order = Orders::findByID($params['id']);
        if (!$order) {
            http_response_code(404);
            echo json_encode(["error" => "Order not found"]);
            exit;
        }

        $order->delete();
        echo json_encode(["status" => "deleted"]);
        exit;
    }
}