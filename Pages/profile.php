<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/profile.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <title>Profile</title>
</head>

<body>
    <?php

    /**
     * profile.php
     * 
     * This file represents a profile page. It is used to display user information and update it.
     * 
     * @category Pages
     * @package  Pages
     */

    require_once 'Blocks/topnav.php';
    require_once '../Model/User.php';
    require_once '../Repository/Repository.php';
    require_once 'Blocks/notification.php';
    $repository = Repository::getInstance();
    $user = $repository->getUserById($_COOKIE['user_id']);
    ?>

    <div class="profile_container">
        <div class="profile">
            <h2>Profile</h2>
            <img src="<?php echo $authService->getProfileImage($user->id) ?>" alt="Profile image">
            <form id="upload_form" action="../Controller/AuthController.php?action=upload_profile_image" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" id="upload_file">
                <input type="submit" value="Upload">
            </form>
            <p>Username: <?php echo htmlspecialchars($user->username) ?></p>
            <p>Name: <?php echo htmlspecialchars($user->name) ?></p>
            <p>Surname: <?php echo htmlspecialchars($user->surname) ?></p>
            <p>Email: <?php echo htmlspecialchars($user->email) ?></p>
            <p>Role: <?php echo htmlspecialchars($user->role->name) ?></p>
        </div>

        <div id="change_profile_data" class="profile">
            <h2>Change profile data</h2>
            <form action="../Controller/AuthController.php?action=update" method="POST">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?php echo $user->name ?>" required>

                <label for="surname">Surname</label>
                <input type="text" name="surname" id="surname" value="<?php echo $user->surname ?>" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo $user->email ?>" required>

                <input type="submit" value="Update">
            </form>
        </div>

        <div id="change_password" class="profile">
            <h2>Change password</h2>
            <form action="../Controller/AuthController.php?action=change_password" method="POST">
                <label for="old_password">Old password</label>
                <input type="password" name="old_password" id="old_password" required>

                <label for="new_password">New password</label>
                <input type="password" name="new_password" id="new_password" required>

                <label for="new_password_confirm">Confirm new password</label>
                <input type="password" name="new_password_confirm" id="new_password_confirm" required>

                <input type="submit" value="Change">
            </form>
        </div>
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