<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/login.css">
    <title>Login</title>
</head>

<body>
    <?php

    require_once 'Blocks/topnav.php'

    ?>

    <div class="login_container">
        <form class="login_form" action="../Controller/AuthController.php?action=login" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>