<?php

/**
 * User
 * 
 * This is a model class representing a user.
 * 
 * @param int $id User ID that is unique for each user in the database (auto-incremented)
 * @param string $name User name
 * @param string $surname User surname
 * @param string $username User username
 * @param string $email User email
 * @param string $password User password
 * @param UserRole $role User role (Admin, Owner, User)
 * @param string $salt User salt used for password hashing
 * 
 * @category Model
 * @package  Model
 */

require_once 'Role.php';

class User {
    public ?int $id;
    public ?string $name;
    public ?string $surname;
    public ?string $username;
    public ?string $email;
    public ?string $password;
    public ?UserRole $role;
    public ?string $salt;

    public function __construct(?int $id, ?string $name, ?string $surname, ?string $username, ?string $email, ?string $password, $role, ?string $salt) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;
        
        if (is_string($role)) {
            $this->role = UserRole::from($role);
        } else {
            $this->role = $role;
        }
    }

}
?>