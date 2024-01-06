<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <title>Login</title>
</head>

<body>
    <?php

    /**
     * login.php
     * 
     * This file represents a login page. It is used to login users.
     * 
     * @category Pages
     * @package  Pages
     */

    require_once 'Blocks/topnav.php';
    require_once 'Blocks/notification.php';

    ?>

    <div class="login_container">
        <form class="login_form" action="../Controller/AuthController.php?action=login" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Login" id="login_button">
        </form>
    </div>

    <?php
    if (isset($_GET['message'])) {
        renderNotification($_GET['message']);
    } elseif (isset($_GET['error'])) {
        renderNotification($_GET['error']);
    }
    ?>
</body>

</html>