<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/tasks.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <link rel="stylesheet" href="../CSS/admin.css">
</head>

<body>

    <?php

    /**
     * admin.php
     * 
     * This file represents an admin page. It is used to display all users and update their information.
     * 
     * @category Pages
     * @package  Pages
     */

    require_once('../Service/AuthService.php');
    require_once 'Blocks/topnav.php';

    $authService = new AuthService();

    /**
     * Check if user is logged in
     */
    if (!isset($_COOKIE['user_id'])) {
        header('Location: ../Pages/login.php');
    }

    /**
     * Check if user is admin
     */
    if (!$authService->isUserRoleAdmin($_COOKIE['user_id'])) {
        header('Location: ../Pages/tasks.php');
    }

    /**
     * Get all users, if page is set get users for that page
     */
    if(isset($_GET['page'])){
        $users = $authService->getAllUsers($_GET['page']);
    } else {
        $users = $authService->getAllUsers(1);
    }

    ?>

    <!-- Search -->
    <div class="search_container">
        <form class="search_form" action="../Controller/AuthController.php?action=admin_search" method="POST">
            <label for="search"></label>
            <input type="text" name="search" id="search" required>
            <input type="submit" value="Search">
        </form>
    </div>

    <?php

    // if search is set display search results
    if (isset($_GET['search'])) {
        $users = $authService->searchUsers($_GET['search']);
    }

    echo '<div class="users_container">';
    foreach ($users as $user) {
        echo '<div class="user_container">';
        echo '<h2>' . htmlspecialchars($user->username) . '</h2>';
        echo '<form action="../Controller/AuthController.php?action=admin_update&user_id=' . $user->id . '" method="POST">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="' . htmlspecialchars($user->name) . '" required>
                        <label for="surname">Surname</label>
                        <input type="text" name="surname" id="surname" value="' . htmlspecialchars($user->surname) . '" required>
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="' . htmlspecialchars($user->username) . '" required>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="' . htmlspecialchars($user->email) . '" required>
                        <input type="submit" value="Update">
                    </form>';
        echo '<form action="../Controller/AuthController.php?action=admin_update_password&user_id=' . $user->id . '" method="POST">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                        <label for="password_confirm">Confirm password</label>
                        <input type="password" name="password_confirm" id="password_confirm" required>
                        <input type="submit" value="Update password">
                    </form>';
        echo '</div>';
    }
    echo '</div>';

    ?>

    <?php
    require_once 'Blocks/notification.php';
    if (isset($_GET['message'])) {
        renderNotification($_GET['message']);
    } elseif (isset($_GET['error'])) {
        renderNotification($_GET['error']);
    }
    ?>

    <div class="pagination">
        <?php
        $pages = $authService->getAllUsersCount() / 5;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        for ($i = 1; $i <= $pages + 1; $i++) {
            echo '<a href="../Pages/admin.php?page=' . $i . '" class="page ' . ($currentPage == $i ? 'current_page' : '') . '">' . $i . '</a>';
        }
        ?>
    </div>

</body>

</html>