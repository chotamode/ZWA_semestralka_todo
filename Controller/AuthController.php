<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    require_once('../Service/AuthService.php');

    $authService = new AuthService();

    switch ($_GET['action']) {
        case 'login':
            if($authService->login($_POST['username'], $_POST['password'])) {
                header('Location: ../Pages/tasks.php');
            } else {
                header('Location: ../Pages/login.php');
            }
            break;

        case 'register':
            // TODO: Add validation
            if($_POST['password'] == $_POST['password_confirm']) {
                $authService->register($_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email'], $_POST['password']);
                header('Location: ../Pages/login.php');
            } else {
                header('Location: ../Pages/register.php');
            }
            break;

        case 'logout':
            $authService->logout();
            header('Location: ../Pages/login.php');
            break;
        
        case 'admin_update':
            if(checkIfUserIsAdminOrOwner($_GET['user_id'], $authService)) {
                $authService->updateUser($_GET['user_id'], $_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email']);
                header('Location: ../Pages/admin.php');
            }
            break;
        case 'admin_update_password':
            if(checkIfUserIsAdminOrOwner($_GET['user_id'], $authService)) {
                if($_POST['password'] == $_POST['password_confirm']) {
                    $authService->updateUserPassword($_GET['user_id'], $_POST['password']);
                    header('Location: ../Pages/admin.php');
                } else {
                    header('Location: ../Pages/admin.php');
                }
            }
            break;

    }

    /**
     * Function to check if user is admin or owner if user is not admin or owner redirect to tasks page
     * 
     * @param int $userId
     * @param AuthService $authService
     * @return bool
     */
    function checkIfUserIsAdminOrOwner($userId, $authService) {
        if(!$authService->isUserRoleAdmin($userId) || !$authService->isUserRoleOwner($userId)) {
            header('Location: ../Pages/tasks.php');
            return false;
        }

        return true;
    }
?>