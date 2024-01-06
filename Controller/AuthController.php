<?php

/**
 * AuthController
 *
 * This file contains functions for handling authentication-related tasks, such as checking if a user is an admin or owner,
 * validating registration data, and uploading a profile image.
 *
 *
 * @category   Controllers
 * @package    Controller
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Service/AuthService.php');

$authService = new AuthService();

session_start();

/**
 * Switch statement to handle different actions
 * 
 * @param string $_GET['action'] Action to be performed
 * @param string $_POST['username'] Username
 * @param string $_POST['password'] Password
 * @param string $_POST['name'] Name
 * @param string $_POST['surname'] Surname
 * @param string $_POST['email'] Email
 * @param string $_POST['password_confirm'] Password confirmation
 * @param string $_POST['old_password'] Old password
 * @param string $_POST['new_password'] New password
 * @param string $_POST['new_password_confirm'] New password confirmation
 * @param string $_POST['search'] Search query
 * @param string $_POST['role'] Role
 * @param int $_GET['user_id'] User ID
 * @param int $_GET['user_id'] User ID
 * @param string $_FILES['file'] File
 */
switch ($_GET['action']) {
    case 'login':
        if ($authService->login($_POST['username'], $_POST['password'])) {
            header('Location: ../Pages/tasks.php');
        } else {
            header('Location: ../Pages/login.php?error=incorrect_login_data');
        }
        break;

    case 'register':

        if (checkRegisterData($_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['password_confirm'], $authService) != 'correct') {
            $_SESSION['register_data'] = $_POST;
            header('Location: ../Pages/register.php?error=' . checkRegisterData($_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['password_confirm'], $authService));
            break;
        }

        $authService->register($_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email'], $_POST['password']);
        header('Location: ../Pages/login.php');
        break;

    case 'logout':
        $authService->logout();
        header('Location: ../Pages/login.php');
        break;

    case 'admin_update':
        if (checkIfUserIsAdminOrOwner($_COOKIE['user_id'], $authService)) {
            if ($authService->isUserRoleOwner($_COOKIE['user_id'])) {
                $role = $_POST['role'];
            } else {
                $role = null;
            }
            $authService->updateUser($_GET['user_id'], $_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email'], null, $role);
            if ($authService->isUserRoleOwner($_COOKIE['user_id'])) {
                header('Location: ../Pages/owner.php?message=user_updated');
            } else {
                header('Location: ../Pages/admin.php?message=user_updated');
            }
        }
        break;
    case 'admin_update_password':
        if (checkIfUserIsAdminOrOwner($_COOKIE['user_id'], $authService)) {
            if ($_POST['password'] == $_POST['password_confirm']) {
                $authService->updateUserPassword($_GET['user_id'], $_POST['password']);
                if ($authService->isUserRoleOwner($_COOKIE['user_id'])) {
                    header('Location: ../Pages/owner.php?message=password_changed');
                } else {
                    header('Location: ../Pages/admin.php?message=password_changed');
                }
            } else {
                if ($authService->isUserRoleOwner($_COOKIE['user_id'])) {
                    header('Location: ../Pages/owner.php?error=passwords_do_not_match');
                } else {
                    header('Location: ../Pages/admin.php?error=passwords_do_not_match');
                }
            }
        }
        break;
    case 'admin_search':
        if (checkIfUserIsAdminOrOwner($_COOKIE['user_id'], $authService)) {
            if ($authService->isUserRoleOwner($_COOKIE['user_id'])) {
                header('Location: ../Pages/owner.php?search=' . $_POST['search']);
            } else {
                header('Location: ../Pages/admin.php?search=' . $_POST['search']);
            }
        }
        break;
    case 'admin_delete':
        if ($authService->isUserRoleOwner($_COOKIE['user_id'])) {
            $authService->deleteUser($_GET['user_id']);
            header('Location: ../Pages/owner.php?message=user_deleted');
        }
        break;
    case 'update':
        if ($authService->updateUser($_COOKIE['user_id'], $_POST['name'], $_POST['surname'], null, $_POST['email'], null, null)) {
            header('Location: ../Pages/profile.php?message=user_updated');
        } else {
            header('Location: ../Pages/profile.php?error=user_not_updated');
        }
        break;
    case 'change_password':
        if ($_POST['new_password'] == $_POST['new_password_confirm']) {
            if ($authService->checkPassword($_COOKIE['user_id'], $_POST['old_password'])) {
                $authService->updateUserPassword($_COOKIE['user_id'], $_POST['new_password']);
                header('Location: ../Pages/profile.php?message=password_changed');
            } else {
                header('Location: ../Pages/profile.php?error=old_password_incorrect');
            }
        } else {
            header('Location: ../Pages/profile.php?error=passwords_do_not_match');
        }
        break;
    case 'upload_profile_image':
        $uploadMessage = $authService->uploadProfileImage($_COOKIE['user_id'], $_FILES['file']);
        if ($uploadMessage == 'success') {
            header('Location: ../Pages/profile.php?message=profile_image_uploaded');
        } else {
            header('Location: ../Pages/profile.php?error=' . $uploadMessage);
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
function checkIfUserIsAdminOrOwner($userId, $authService)
{
    if ($authService->isUserRoleAdmin($userId) || $authService->isUserRoleOwner($userId)) {
        return true;
    } else {
        header('Location: ../Pages/tasks.php');
        return false;
    }
}

/**
 * Function to check register data, if data is correct returning 'correct' else returning error message
 * 
 * @param string $name
 * @param string $surname
 * @param string $username
 * @param string $email
 * @param string $password
 * @param string $passwordConfirm
 * @return string Message if data is incorrect or 'correct' if data is correct
 */
function checkRegisterData($name, $surname, $username, $email, $password, $passwordConfirm, $authService)
{
    $fields = [
        'name' => $name,
        'surname' => $surname,
        'username' => $username,
        'email' => $email,
        'password' => $password
    ];

    foreach ($fields as $field => $value) {
        if (strlen($value) < 3) {
            return $field . '_too_short';
        } elseif (strlen($value) > 50) {
            return $field . '_too_long';
        }
    }

    if ($password != $passwordConfirm) {
        return 'passwords_do_not_match';
    }

    if ($authService->getUserByUsername($username) != null) {
        return 'username_taken';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'email_invalid';
    }

    if ($authService->getUserByEmail($email) != null) {
        return 'email_taken';
    }

    return 'correct';
}
