<?php
require_once 'auth.php';
require_login();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Password Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Dashboard</h1>
    <p>Welcome, <?=htmlspecialchars($_SESSION['user']['user_name'])?> (<code><?=htmlspecialchars($_SESSION['user']['user_username'])?></code>)</p>

    <nav class="nav">
        <a href="users.php">Users</a>
        <a href="pass.php">Passwords</a>
        <a href="vendor.php">Vendors</a>
        <a href="?logout=1">Logout</a>
    </nav>

    <section>
        <h2>Quick Links</h2>
        <ul>
            <li><a href="users.php">Manage Users</a></li>
            <li><a href="pass.php">Manage Passwords</a></li>
            <li><a href="vendor.php">Manage Vendors</a></li>
        </ul>
    </section>
</div>
</body>
</html>
