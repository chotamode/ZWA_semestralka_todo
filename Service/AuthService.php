<?php

require_once '../Repository/Repository.php';
require_once '../Model/User.php';
require_once '../Model/Role.php';

/**
 * AuthService
 * 
 * This class is used for authentication and authorization. Gives another layer of abstraction between the controller and the repository.
 * 
 * @category Service
 * @package  Service
 */

class AuthService
{
    private $repository;

    public function __construct()
    {
        $this->repository = Repository::getInstance();
    }

    public function login($username, $password)
    {
        $user = $this->repository->getUserByUsername($username);
        if ($user == null) {
            return false;
        }
        if (password_verify($password . $user->salt, $user->password)) {
            setcookie('user_id', $user->id, time() + (86400 * 30), "/");
            return true;
        }
        return false;
    }

    public function logout()
    {
        setcookie('user_id', '', time() - 3600, "/");
    }

    public function register($name, $surname, $username, $email, $password)
    {
        $salt = bin2hex(random_bytes(32));
        $hashedWithSaltPassword = password_hash($password . $salt, PASSWORD_DEFAULT);
        $user = new User(0, $name, $surname, $username, $email, $hashedWithSaltPassword, UserRole::User, $salt);
        $this->repository->createUser($user);
    }

    public function getUserById($id)
    {
        return $this->repository->getUserById($id);
    }

    public function getUserByUsername($username)
    {
        return $this->repository->getUserByUsername($username);
    }

    public function getUserByEmail($email)
    {
        return $this->repository->getUserByEmail($email);
    }

    public function isUserRoleAdmin($userId)
    {
        $user = $this->repository->getUserById($userId);
        return $user->role == UserRole::Admin;
    }

    public function isUserRoleOwner($userId)
    {
        $user = $this->repository->getUserById($userId);
        return $user->role == UserRole::Owner;
    }

    /**
     * Get all users
     * 
     * @param int $page Page number. By default <= -1 (all users), 0 (first page), 1, 2, 3, ...
     */
    public function getAllUsers($page=-1)
    {
        if($page == 0){
            return $this->repository->getAllUsers(1);
        }
        if($page <= -1){
            return $this->repository->getAllUsers();
        }else{
            return $this->repository->getAllUsers($page);
        }
    }

    public function getAllUsersCount()
    {
        return $this->repository->getTotalUsers();
    }

    public function deleteUser($id)
    {
        $this->repository->deleteUser($id);
    }

    /**
     * Update user. If parameter is null, it will not be updated.
     * 
     * @param int $id User ID
     * @param string $name User name
     * @param string $surname User surname
     * @param string $username User username
     * @param string $email User email
     * @param string $password User password
     * @param UserRole $role User role (Admin, Owner, User)
     * 
     * @return void
     */
    public function updateUser($id, $name, $surname, $username, $email, $password, $role)
    {

        $user = $this->repository->getUserById($id);
        if ($name == null) {
            $name = $user->name;
        }
        if ($surname == null) {
            $surname = $user->surname;
        }
        if ($username == null) {
            $username = $user->username;
        }
        if ($email == null) {
            $email = $user->email;
        }
        if ($password == null) {
            $password = $user->password;
        }
        if ($role == null) {
            $role = $user->role;
        }
        if ($password != null) {
            $salt = bin2hex(random_bytes(32));
            $hashedWithSaltPassword = password_hash($password . $salt, PASSWORD_DEFAULT);
            $password = $hashedWithSaltPassword;
        }
        $salt = $user->salt;
        $user = new User($id, $name, $surname, $username, $email, $password, $role, $salt);
        $this->repository->updateUser($user);
    }

    public function updateUserPassword($id, $password)
    {
        $salt = bin2hex(random_bytes(32));
        $hashedWithSaltPassword = password_hash($password . $salt, PASSWORD_DEFAULT);
        $this->repository->updateUserPassword($id, $hashedWithSaltPassword, $salt);
    }

    public function searchUsers($search)
    {
        return $this->repository->searchUsers($search);
    }

    public function checkPassword($id, $password)
    {
        $user = $this->repository->getUserById($id);
        return password_verify($password . $user->salt, $user->password);
    }

    public function checkIfUsernameExists($username)
    {
        $user = $this->repository->getUserByUsername($username);
        return $user != null;
    }

    public function checkIfEmailExists($email)
    {
        $user = $this->repository->getUserByEmail($email);
        return $user != null;
    }

    /**
     * Upload profile image. If user already have profile image, it will be deleted and replaced with new one.
     * 
     * @param int $userId User ID
     * @param array $file File array from $_FILES
     */
    public function uploadProfileImage($userId, $file)
    {
        $target_dir = "../UserImages/";
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $target_file = $target_dir . uniqid() . "." . strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if ($file["size"] > 500000) {
                return "file_too_large";
            }
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                return "wrong_file_type";
            }
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                if($this->repository->isUserAlreadyHaveProfileImage($userId)){
                    $oldImagePath = $this->repository->getUserProfileImage($userId);
                    unlink($oldImagePath);
        
                }
                $this->repository->uploadProfileImage($userId, $target_file);
                return "success";
            } else {
                return "error_uploading_file";
            }
        } else {
            return "not_an_image";
        }
    }

    /**
     * Get user profile image path. If user does not have profile image, it will return default profile image.
     * 
     * @param int $userId User ID
     */
    public function getProfileImage($userId)
    {
        $profileImage = $this->repository->getUserProfileImage($userId);
        if($profileImage == null){
            return "../Images/profile_filler.png";
        }else{
            return $profileImage;
        }
    }
}