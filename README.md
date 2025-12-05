# Food-Ordering-Management-System
A web application developed in PHP using an MVC framework with ORM, design patterns, and API integration. The project demonstrates structured backend development, clean separation of concerns, and integration of external services within a scalable architecture.

# Technologies Used
- PHP 8+
- MVC Framework Structure
- Object-Relational Mapping (ORM)
- Design Patterns (Controller, Singleton, Factory, etc.)
- REST API Integration
- MySQL Database
- Apache NetBeans IDE 25
- XAMPP (Apache + MySQL)

# Development Environment
1. NetBeans IDE
The project is created and executed using Apache NetBeans IDE 25.

2.XAMPP
- Start Apache and MySQL
- Place the **Assignment** folder inside: **xampp/htdocs**

3.Create database & import SQL
- Create a database named **ecommerce_db** at **http://localhost/phpmyadmin/**
- import the provided ecommerce_db.sql to the database.

4. Run the project
Open browser: **http://localhost/Assignment/index.php**

# Project Structure (MVC)
Assignment/
- index.php – Entry point of the application
- avatar.png / drink.jpg / food.jpg – Sample image assets

Core/ – Core MVC components
- BaseController.php – Shared controller logic
- Db.php – Database connection using ORM
- router.php – Simple router for handling requests

Controller/ – All application controllers

Model/ – ORM models representing database tables

View/ – Views responsible for rendering UI

FactoryPattern/ – Factory Design Pattern implementation

CommandPattern/ – Command Design Pattern implementation

ObserverPattern/ – Observer Design Pattern implementation

lib/PHPMailer/ – PHPMailer library used for email functionality
