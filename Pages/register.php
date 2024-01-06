<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/register.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <script src="../JS/register.js"></script>
    <title>Register</title>
</head>

<body id="register_body">
    <?php 

    /**
     * register.php
     * 
     * This file represents a register page. It is used to register users.
     * 
     * @category Pages
     * @package  Pages
     */

     
    require_once 'Blocks/topnav.php';
    require_once 'Blocks/notification.php';

    ?>
    <div class="register_container">
        <form class="register_form" action="../Controller/AuthController.php?action=register" method="POST">

            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo isset($_SESSION['register_data']['username']) ? htmlspecialchars($_SESSION['register_data']['username']) : ''; ?>" required class="">

            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo isset($_SESSION['register_data']['name']) ? htmlspecialchars($_SESSION['register_data']['name']) : ''; ?>" required>

            <label for="surname">Surname</label>
            <input type="text" name="surname" id="surname" value="<?php echo isset($_SESSION['register_data']['surname']) ? htmlspecialchars($_SESSION['register_data']['surname']) : ''; ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['register_data']['email']) ? htmlspecialchars($_SESSION['register_data']['email']) : ''; ?>" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="password_confirm">Confirm Password</label>
            <input type="password" name="password_confirm" id="password_confirm" required>

            <input type="submit" value="Register" id="register_button">

            <p>All fields are required</p>
        </form>
        <?php
        if (isset($_SESSION['register_data'])) {
            unset($_SESSION['register_data']);
        }
        ?>
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