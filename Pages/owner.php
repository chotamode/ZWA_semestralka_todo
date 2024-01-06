<!-- page for owner where he can do everything admin can but also ca make someone admin and also he can see tasks and redact them -->

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Owner</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/tasks.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <link rel="stylesheet" href="../CSS/admin.css">
</head>

<body>

    <?php

    /**
     * owner.php
     * 
     * This file represents an owner page. It is used to display all users and update their information. Owner can also make someone admin, delete users.
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
     * Check if user is owner
     */
    if (!$authService->isUserRoleOwner($_COOKIE['user_id'])) {
        header('Location: ../Pages/tasks.php');
    }

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
                        <label for="role">Role</label>
                        <select name="role" id="role">
                            <option value="User" ' . ($user->role == UserRole::User ? 'selected' : '') . '>User</option>
                            <option value="Admin" ' . ($user->role ==  UserRole::Admin ? 'selected' : '') . '>Admin</option>
                            <option value="Owner" ' . ($user->role ==  UserRole::Owner ? 'selected' : '') . '>Owner</option>
                        </select>
                        <input type="submit" value="Update">
                    </form>';
        echo '<form action="../Controller/AuthController.php?action=admin_update_password&user_id=' . $user->id . '" method="POST">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                        <label for="password_confirm">Confirm password</label>
                        <input type="password" name="password_confirm" id="password_confirm" required>
                        <input type="submit" value="Update password">
                    </form>';
        echo '<form action="../Controller/AuthController.php?action=admin_delete&user_id=' . $user->id . '" method="POST">
                        <input type="submit" value="Delete">
                    </form>';
        echo '</div>';
    }
    echo '</div>';

?>

    <div class="pagination">
        <?php
        $pages = $authService->getAllUsersCount() / 5;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        for ($i = 1; $i <= $pages + 1; $i++) {
            echo '<a href="../Pages/owner.php?page=' . $i . '" class="page ' . ($currentPage == $i ? 'current_page' : '') . '">' . $i . '</a>';
        }
        ?>
    </div>

</body>

</html>