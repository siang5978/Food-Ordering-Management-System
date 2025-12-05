<?php
require_once 'Command.php';

class RemoveCartItemCommand implements Command {
    private $cart;

    public function __construct($cart) {
        $this->cart = $cart;
    }

    #[\Override]
    public function execute() {
        $this->cart->delete();
    }
}
