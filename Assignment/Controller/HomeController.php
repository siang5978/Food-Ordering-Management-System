<?php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Model/Products.php';

class HomeController extends \Core\BaseController {
    public function index(){
        $foods = Products::filterByType('Food');
        $drinks = Products::filterByType('Drink');
        
        $this->display(__DIR__ . '/../View/Home.php', ['foods' => $foods, 'drinks' => $drinks]);
    }
    
    public function adminPanel(){
        $this->displayAdmin(__DIR__ . '/../View/Home_Admin.php');
    }
}
