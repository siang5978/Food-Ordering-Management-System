<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YUM FOMS</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="page-container">
    <header>
        <div class="header-container">
            <h1 class="logo">YUM FOMS</h1>
            <nav class="menu">
                <ul>
                    <li><a href="/Assignment/index.php">Home</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="/Assignment/index.php/Cart">Cart</a></li>
                        <li><a href="/Assignment/index.php/Profile">Profile</a></li>
                        <li><a href="/Assignment/index.php/Logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/Assignment/index.php/Login">Login</a></li>
                    <?php endif; ?>

                    <?php if ($role === "admin"): ?>
                        <li><a href="/Assignment/index.php/AdminPanel">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>