<?php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Model/Cart.php';
require_once __DIR__ . '/../Model/Orders.php';
require_once __DIR__ . '/../Model/Order_Items.php';

//Command Design Pattern
require_once __DIR__ . '/../CommandPattern/Command.php';
require_once __DIR__ . '/../CommandPattern/CartInvoker.php';
require_once __DIR__ . '/../CommandPattern/AddtoCartCommand.php';
require_once __DIR__ . '/../CommandPattern/RemoveCartItemCommand.php';
require_once __DIR__ . '/../CommandPattern/UpdateCartItemCommand.php';
require_once __DIR__ . '/../CommandPattern/CheckOutCommand.php';

class OrderController extends \Core\BaseController {
    //Authentication checking(Security Practices)
    private function checkLogin() {
        if (!isset($_SESSION['id'])) {
            header("Location: /Assignment/index.php/Login");
            return;
        }
    }
    
    //Verify the user control their own cart (Security Practices)
    private function verifyCartOwnership($userID) {
        $cartItems = Cart::findByUser($userID);
        foreach ($cartItems as $item) {
            if ($item->getUserID() != $_SESSION['id']) {
                header("Location: /Assignment/index.php/error");
                return;
            }
        }
        return $cartItems;
    }

    //cart page
    public function getCart(){
        $this->checkLogin();
        $cartItems = $this->verifyCartOwnership($_SESSION['id']);
        
        $CartItems = [];
        foreach ($cartItems as $item) {
            $product = Products::find($item->getProductID());
            if ($product) {
                $CartItems[] = [
                    'item' => $item,
                    'product' => $product
                ];
            }
        }

        $this->display(__DIR__ . '/../View/Cart.php', ['CartItems' => $CartItems]);
    }
    
    //create cartItem function [Command Pattern]
    public function addCart($params){
        $this->checkLogin();
        
        $userID = $_SESSION['id'];
        $productID = $params['id'];
        
        $cart = new Cart(null, $userID, $productID, 1);
        
        $command = new AddToCartCommand($cart);
        $invoker = new CartInvoker();
        $invoker->setCommand($command);
        $invoker->execute();
        
        header("Location: /Assignment/index.php/Cart");
    }
    
    //update cartItem function [Command Pattern]
    public function updateCartItem() {
        $this->checkLogin();
        
        $cartID = $_POST['cartid'];
        $quantity = $_POST['quantity'];
        
        if (empty($quantity) || !preg_match("/^[1-9][0-9]*$/", $quantity)) {
            $errorMessage = "Quantity must be a whole number greater than 0.";
            $Cart = Cart::findByUser($_SESSION['id']);
            $CartItems = $this->verifyCartOwnership($_SESSION['id']);
            $this->display(__DIR__ . '/../View/Cart.php', ['CartItems' => $CartItems, 'errorMessage' => $errorMessage]);
            return;
        }

        $cartItems = $this->verifyCartOwnership($_SESSION['id']);
        
        $cart = null;
        foreach ($cartItems as $item) {
            if ($item->getCartID() == $cartID) {
                $cart = $item;
            }
        }
        $cart->setQuantity($quantity);
        $command = new UpdateCartItemCommand($cart);
        $invoker = new CartInvoker();
        $invoker->setCommand($command);
        $invoker->execute();
        header("Location: /Assignment/index.php/Cart");
    }
    
    //delete cartItem function [Command Pattern]
    public function removeCartItem() {
        $this->checkLogin();
        
        $cartID = $_POST['cartid'];
        
        $cartItems = $this->verifyCartOwnership($_SESSION['id']);

        $cart = null;
        foreach ($cartItems as $item) {
            if ($item->getCartID() == $cartID) {
                $cart = $item;
            }
        }
        $command = new RemoveCartItemCommand($cart);
        $invoker = new CartInvoker();
        $invoker->setCommand($command);
        $invoker->execute();
       
        header("Location: /Assignment/index.php/Cart");
    }
    
    //checkOut function [Command Pattern]
    public function checkOut(){
        $this->checkLogin();
        $userID = $_SESSION['id'];
        $command = new CheckOutCommand($userID);
        $invoker = new CartInvoker();
        $invoker->setCommand($command);
        $orderData = $invoker->execute();

        $this->display(__DIR__ . '/../View/Payment.php',['orderData' => $orderData]);
    }
    
    //payment page
    public function UpdatePayment(){
        $this->checkLogin();
        
        $OrderID = $_POST['orderID'];
        $PaymentStatus = 'Paid';
        $order = Orders::findByID($OrderID);
        $order->setPaymentStatus($PaymentStatus);
        $order->update();
        
        header("Location: /Assignment/index.php/History");
    }
    
    //history page
    public function history() {
        $this->checkLogin();
        
        $orders = Orders::findByUser($_SESSION['id']);

        $history = [];

        foreach ($orders as $order) {
            $orderItems = Order_Items::findByOrder($order->getOrderID());
            $itemsWithProduct = [];

            foreach ($orderItems as $item) {
                $product = Products::find($item->getProductID());
                $itemsWithProduct[] = [
                    'item' => $item,
                    'productName' => $product->getProductName()
                ];
            }

            $history[] = [
                'order' => $order,
                'items' => $itemsWithProduct
            ];
        }

        $this->display(__DIR__ . '/../View/History.php', ['history' => $history]);
    }
}