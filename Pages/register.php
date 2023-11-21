<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../CSS/register.css">
        <title>Register</title>
    </head>

    <body>
        <?php require_once 'topnav.php' ?>
        <div class="register_container">
            <form class="register_form" action="../Service/register.php" method="post">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>

                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>

                <label for="surname">Surname</label>
                <input type="text" name="surname" id="surname" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <label for="password_confirm">Confirm Password</label>
                <input type="password" name="password_confirm" id="password_confirm" required>

                <input type="submit" value="Register">
            </form>
        </div>
    </body>
</html>