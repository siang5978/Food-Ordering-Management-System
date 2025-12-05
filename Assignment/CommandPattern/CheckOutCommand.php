<?php
require_once 'Command.php';

class CheckOutCommand implements Command {
    private $userID;

    public function __construct($userID) {
        $this->userID = $userID;
    }

    #[\Override]
    public function execute() {
        $cartItems = Cart::findByUser($this->userID);
        
        if (empty($cartItems)) {
            header("Location: /Assignment/index.php/Cart");
            exit;
        }
        
        foreach ($cartItems as $item) {
            if ($item->getUserID() != $_SESSION['id']) {
                header("Location: /Assignment/index.php/error");
                return;
            }
        }
        
        $totalAmount = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = Products::find($item->getProductID());
            if ($product) {
                $price = $product->getPrice();
                $quantity = $item->getQuantity();

                if ($product->getStock() < $quantity) {
                    throw new Exception("Not enough stock for product: " . $product->getProductName());
                }

                $totalAmount += $price * $quantity;

                $orderItem = new Order_Items(null, null, $product->getProductID(), $quantity, $price);
                $orderItems[] = $orderItem;

                $product->setStock($product->getStock() - $quantity);
                
                $product->update();
            }
        }
        
        $accountJson = file_get_contents("http://localhost/Assignment/index.php/api/loyaltyReport/{$this->userID}");
        $accountData = $accountJson ? json_decode($accountJson, true) : null;
        $loyaltyLevel = $accountData['loyaltyLevel'] ?? 'Bronze';
        
        $discountRate = 0;
        switch ($loyaltyLevel) {
            case 'Gold':
                $discountRate = 0.2;
                break;
            case 'Silver':
                $discountRate = 0.1;
                break;
            case 'Bronze':
            default:
                $discountRate = 0;
                break;
        }
        
        $totalAmountAfterDiscount = $totalAmount * (1 - $discountRate);

        $order = new Orders(null, $this->userID, null, $totalAmountAfterDiscount, 'Pending');
        $order->insert();

        foreach ($orderItems as $orderItem) {
            $orderItem->setOrderID($order->getOrderID());
            $orderItem->insert();
        }

        foreach ($cartItems as $item) {
            $item->delete();
        }

        return [
            "orderID" => $order->getOrderID(),
            "totalAmount" => $totalAmount,
            "discountRate" => $discountRate,
            "totalAmountAfterDiscount" => $totalAmountAfterDiscount, 
            "loyaltyLevel" => $loyaltyLevel
        ];
    }
}
