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
        <title>Profile</title>
    </head>

    <body>
        <?php 
            require_once 'Blocks/topnav.php';
            require_once '../Model/User.php';
            require_once '../Repository/Repository.php';
            $repository = new Repository();
            $user = $repository->getUserById($_COOKIE['user_id']);
        ?>

        <div class="profile_container">
            <div class="profile">
                <h2>Profile</h2>
                <img src="../Images/profile_filler.png" alt="Profile Picture">
                <p>Username: <?php echo $user->username ?></p>
                <p>Name: <?php echo $user->name ?></p>
                <p>Surname: <?php echo $user->surname ?></p>
                <p>Email: <?php echo $user->email ?></p>
                <p>Role: <?php echo $user->role->name ?></p>
            </div>
        </div>

    </body>
</html>