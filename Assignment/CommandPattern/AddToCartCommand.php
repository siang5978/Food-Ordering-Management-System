<?php
require_once 'Command.php';

class AddToCartCommand implements Command {
    private $cart;

    public function __construct(Cart $cart) {
        $this->cart = $cart;
    }
    
    #[\Override]
    public function execute() {
        $this->cart->insert();
    }
}

