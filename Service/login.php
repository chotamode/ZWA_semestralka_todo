<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

require_once '../Model/User.php';
require_once '../Model/Role.php';
require_once '../Repository/Repository.php';

// login user if form is submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $repository = new Repository();
    $users = $repository->getAllUsers();

    // Check if username exists
    foreach ($users as $user) {
        if ($user->username === $_POST['username']) {
            // Check if password is correct
            if ($user->password === $_POST['password']) {

                setcookie('user_id', $user->id, time() + (86400 * 30), "/"); // 86400 = 1 day

                // Redirect to home page
                header('Location: ../Pages/tasks.php');
                exit();
            }
        }
    }

    // Redirect to login page
    header('Location: ../Pages/login.php?error=login');
    exit();
}

?>