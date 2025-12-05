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
│
├── index.php                # Entry point of the application
├── avatar.png               # Sample assets
├── drink.jpg
├── food.jpg
│
├── Core/                    # Core MVC components
│   ├── BaseController.php   # Base controller with shared logic
│   ├── Db.php               # Database connection (ORM-based)
│   └── router.php           # Simple router for handling requests
│
├── Controller/              # All application controllers
│   └── ... (e.g., ProductController, UserController)
│
├── Model/                   # ORM models representing database tables
│   └── ... (e.g., Product.php, User.php)
│
├── View/                    # Views for rendering UI
│   └── ... (HTML/PHP templates)
│
├── FactoryPattern/          # Factory Design Pattern implementation
│   └── ... (factory classes)
│
├── CommandPattern/          # Command Design Pattern implementation
│   └── ... (command classes)
│
├── ObserverPattern/         # Observer Design Pattern implementation
│   └── ... (observer classes)
│
├── lib/PHPMailer/           # PHPMailer library for email features
│   └── ... (vendor files)
│
└── nbproject/               # NetBeans project configuration files
