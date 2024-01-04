<!-- Admin page where admin can change all info in user accounts and their passwords 

    Admin is a user with advanced permissions to manage user accounts and support other users.

    there is table with all users and their info and buttons to change their info and passwords
-->

<?php
    require_once('../Service/AuthService.php');
    require_once 'Blocks/topnav.php';
    
    $authService = new AuthService();

    // check if user is logged in
    if(!isset($_COOKIE['user_id'])) {
        header('Location: ../Pages/login.php');
    }

    // check if user is admin or owner
    if(!$authService->isUserRoleAdmin($_COOKIE['user_id'])) {
        header('Location: ../Pages/tasks.php');
    }

    $users = $authService->getAllUsers();

    foreach ($users as $user) {
        echo '<h2>' . $user->username . '</h2>';
        echo '<form action="../Controller/AuthController.php?action=admin_update&user_id=' . $user->id . '" method="POST">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="' . $user->name . '" required>
                    <label for="surname">Surname</label>
                    <input type="text" name="surname" id="surname" value="' . $user->surname . '" required>
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="' . $user->username . '" required>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="' . $user->email . '" required>
                    <input type="submit" value="Update">
                </form>';
        echo '<form action="../Controller/AuthController.php?action=admin_update_password&user_id=' . $user->id . '" method="POST">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <label for="password_confirm">Confirm password</label>
                    <input type="password" name="password_confirm" id="password_confirm" required>
                    <input type="submit" value="Update password">
                </form>';
    }


?>