<?php
require_once 'Command.php';
class CartInvoker {
    private $command;

    public function setCommand(Command $command) {
        $this->command = $command;
    }

    public function execute() {
        return $this->command->execute();
    }
}
