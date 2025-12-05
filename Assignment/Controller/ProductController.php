<?php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Model/Products.php';

//Factory Design Pattern
require_once __DIR__ . '/../FactoryPattern/ProductFactory.php';

class ProductController extends \Core\BaseController{
    //product detail page
    public function detail($params){
        $product = Products::find($params['id']);
        if (!$product) {
            die('Product not found.');
        }
        
        $this->display(__DIR__ . '/../View/Product_Detail.php', ['product' => $product]);
    }
    
    //add product page(admin panel)
    public function addProduct(){
        $this->displayAdmin(__DIR__ . '/../View/AddProduct_Admin.php');
    }
    
}
