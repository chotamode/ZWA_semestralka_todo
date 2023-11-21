<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

require_once '../Model/User.php';
require_once '../Model/Role.php';
require_once '../Repository/Repository.php';

// Register user if form is submitted
if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
    $repository = new Repository();
    $users = $repository->getAllUsers();

    // Check if username is already taken
    foreach ($users as $user) {
        if ($user->username === $_POST['username']) {
            header('Location: ../Pages/register.php?error=username');
            exit();
        }
    }

    // Check if email is already taken
    foreach ($users as $user) {
        if ($user->email === $_POST['email']) {
            header('Location: ../Pages/register.php?error=email');
            exit();
        }
    }

    // Check if passwords match
    if ($_POST['password'] !== $_POST['password_confirm']) {
        header('Location: ../Pages/register.php?error=password');
        exit();
    }

    // Create user
    $user = new User(
        0,
        $_POST['name'],
        $_POST['surname'],
        $_POST['username'],
        $_POST['email'],
        $_POST['password'],
        UserRole::User
    );

    echo 'creating user';

    // Save user, if successful echo success message
    $repository->createUser($user);
    

    // Redirect to login page
    header('Location: ../Pages/login.php');
    exit();
}

?>