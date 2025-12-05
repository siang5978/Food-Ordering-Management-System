<?php

//Session start
session_start();

require_once __DIR__ . '/Core/Router.php';
require_once __DIR__ . '/Core/Db.php';
require_once __DIR__ . '/Controller/HomeController.php';
require_once __DIR__ . '/Controller/ProductController.php';
require_once __DIR__ . '/Controller/AccountController.php';
require_once __DIR__ . '/Controller/OrderController.php';
require_once __DIR__ . '/Controller/api/ProductApiController.php';
require_once __DIR__ . '/Controller/api/AccountApiController.php';
require_once __DIR__ . '/Controller/api/OrderApiController.php';

//route
use Core\Router;

Db::init("localhost", "root", "", "ecommerce_db");
$router = new Router();

//Home Page
$router->addroute('GET', '/Assignment/index.php', [HomeController::class, 'index']);

//Home Page(Admin Panel)
$router->addRoute('GET', '/Assignment/index.php/AdminPanel', [HomeController::class, 'adminPanel']);

//Product Model
//1.Product Detail Page
$router->addRoute('GET', '/Assignment/index.php/productDetail/{id}', [ProductController::class, 'detail']);

//2.Product List Page(Admin Panel) - json api
$router->addRoute('GET', '/Assignment/index.php/api/product', [ProductApiController::class, 'getAll']);

//3.Modify Product(Admin Panel) - json api
$router->addRoute('GET', '/Assignment/index.php/api/product/{id}', [ProductApiController::class, 'getById']);

//4.Update Function - json api
$router->addRoute('PUT', '/Assignment/index.php/api/product/{id}', [ProductApiController::class, 'update']);

//5.Delete Functoin - json api
$router->addRoute('DELETE', '/Assignment/index.php/api/product/{id}', [ProductApiController::class, 'delete']);

//6.Add Product Page (Admin Panel)
$router->addRoute('GET', '/Assignment/index.php/AddProduct', [ProductController::class, 'addProduct']);

//7.Add Function - json api
$router->addRoute('POST', '/Assignment/index.php/api/product', [ProductApiController::class, 'add']);

//8.Sales Report
$router->addRoute('GET', '/Assignment/index.php/api/salesReport', [ProductApiController::class, 'salesReport']);


//User Management Model
//1.Login Page
$router->addRoute('GET', '/Assignment/index.php/Login', [AccountController::class, 'login']);

//2.Authentication Function
$router->addRoute('POST', '/Assignment/index.php/Login/Authenticate', [AccountController::class, 'authenticate']);

//3.Register Page
$router->addRoute('GET', '/Assignment/index.php/Register', [AccountController::class, 'register']);

//4.Create Account Function
$router->addRoute('POST', '/Assignment/index.php/Register/Create', [AccountController::class, 'create']);

//5.Logout Function
$router->addRoute('GET', '/Assignment/index.php/Logout', [AccountController::class, 'logout']);

//6.Profile Page
$router->addRoute('GET', '/Assignment/index.php/Profile', [AccountController::class, 'profile']);

//7.Edit Profile Page
$router->addRoute('GET', '/Assignment/index.php/EditProfile', [AccountController::class, 'edit']);

//8.Update Edit Profile Funtion
$router->addRoute('POST', '/Assignment/index.php/UpdateProfile', [AccountController::class, 'update']);

//9.Account List(Admin Panel) - json api
$router->addRoute('GET', '/Assignment/index.php/api/Account', [AccountApiController::class, 'getAll']);

//10.Modify Account(Admin Panel) - json api
$router->addRoute('GET', '/Assignment/index.php/api/Account/{id}', [AccountApiController::class, 'getById']);

//11.Update Modfiy Account Function - json api
$router->addRoute('PUT', '/Assignment/index.php/api/Account/{id}', [AccountApiController::class, 'update']);

//12.Delete Function - json api
$router->addRoute('DELETE', '/Assignment/index.php/api/Account/{id}', [AccountApiController::class, 'delete']);

//13.Change Password Page
$router->addRoute('GET', '/Assignment/index.php/ChangePass', [AccountController::class, 'changePass']);

//14.Find Account With Email
$router->addRoute('POST', '/Assignment/index.php/SendPasswordReset', [AccountController::class, 'sendPasswordReset']);

//15.Verify OTP Code Page
$router->addRoute('GET', '/Assignment/index.php/VerifyOTP', [AccountController::class, 'verifyOTPpage']);

//17.Verify OTP Code Function
$router->addRoute('POST', '/Assignment/index.php/VerifyOTP', [AccountController::class, 'verifyOTP']);

//18.Update Password page
$router->addRoute('GET', '/Assignment/index.php/updatePass', [AccountController::class, 'updatePassPage']);

//19.Update Password Function
$router->addRoute('POST', '/Assignment/index.php/updatePass', [AccountController::class, 'updatePass']);

//20.Loyalty Report Page
$router->addRoute('GET', '/Assignment/index.php/api/loyaltyReport', [AccountApiController::class, 'loyaltyReport']);
$router->addRoute('GET', '/Assignment/index.php/api/loyaltyReport/{id}', [AccountApiController::class, 'loyaltyByID']);

//Order Model
//1.Cart Page
$router->addRoute('GET', '/Assignment/index.php/Cart', [OrderController::class, 'getCart']);

//2.Add to Cart
$router->addRoute('GET', '/Assignment/index.php/AddCart/{id}', [OrderController::class, 'addCart']);

//3.Update Cart
$router->addRoute('POST', '/Assignment/index.php/UpdateCartItem', [OrderController::class, 'updateCartItem']);

//4.Remove Cart Item
$router->addRoute('POST', '/Assignment/index.php/RemoveCartItem', [OrderController::class, 'removeCartItem']);

//5.CheckOut
$router->addRoute('POST', '/Assignment/index.php/Checkout', [OrderController::class, 'checkOut']);

//6.Payment
$router->addRoute('GET', '/Assignment/index.php/Payment', [OrderController::class, 'payment']);

//7.Update Status Payment
$router->addRoute('POST', '/Assignment/index.php/UpdatePayment', [OrderController::class, 'updatePayment']);

//8.Order History Page
$router->addRoute('GET', '/Assignment/index.php/History', [OrderController::class, 'history']);

//9.Order History(Admin Panel) - json api
$router->addRoute('GET', '/Assignment/index.php/api/orders', [OrderApiController::class, 'getAll']);

//11.Update Modify Function - json api
$router->addRoute('GET', '/Assignment/index.php/api/orders/{id}', [OrderApiController::class, 'getById']);
$router->addRoute('PUT', '/Assignment/index.php/api/orders/{id}', [OrderApiController::class, 'update']);

//12.Delete History - json api
$router->addRoute('DELETE', '/Assignment/index.php/api/orders/{id}', [OrderApiController::class, 'delete']);

echo $router->resolve();
