<?php
namespace Core;

class BaseController {
    protected $data = [];
    
    public function __construct() {
        
        $this->data['isLoggedIn'] = isset($_SESSION['id']);
        $this->data['role'] = $_SESSION['role'] ?? 'user';
    }
    
    protected function display($viewPath, $extraData = []) {
        $data = array_merge($this->data, $extraData);
        extract($data);
        include __DIR__ . '/../View/layout/Layout.php';
    }
    
    protected function displayAdmin($viewPath, $extraData = []) {
        $data = array_merge($this->data, $extraData);
        extract($data);
        include __DIR__ . '/../View/layout/Layout_Admin.php';
    }
}