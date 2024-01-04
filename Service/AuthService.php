<?php

require_once '../Repository/Repository.php';
require_once '../Model/User.php';
require_once '../Model/Role.php';

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
        if (password_verify($password, $user->password)) {
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
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(0, $name, $surname, $username, $email, $hashedPassword, UserRole::User);
        $this->repository->createUser($user);
    }

    public function getUserById($id)
    {
        return $this->repository->getUserById($id);
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

    public function getAllUsers()
    {
        return $this->repository->getAllUsers();
    }

    public function updateUser($id, $name, $surname, $username, $email)
    {
        $user = new User($id, $name, $surname, $username, $email, '', UserRole::User);
        $this->repository->updateUser($user);
    }

    public function updateUserPassword($id, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->repository->updateUserPassword($id, $hashedPassword);
    }
}