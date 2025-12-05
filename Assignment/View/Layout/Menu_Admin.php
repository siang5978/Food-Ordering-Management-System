<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="page-container">
    <header>
        <div class="header-container">
            <h1 class="logo">Admin Panel</h1>
            <nav class="menu">
                <ul>
                    <?php if ($isLoggedIn && $role === "admin"): ?>
                    <li><a href="/Assignment/view/Product_Admin.php">Product</a></li>
                    <li><a href="/Assignment/View/SalesReport.php">Sales Report</a><li>
                    <li><a href="/Assignment/view/AccList.php">Account</a></li>
                    <li><a href="/Assignment/view/loyaltyReport.php">Loyalty Report</a></li>    
                    <li><a href="/Assignment/view/History_Admin.php">Order History</a></li>    
                    <li><a href="/Assignment/index.php">Leave Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>