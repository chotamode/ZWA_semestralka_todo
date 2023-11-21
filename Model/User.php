<?php

class User {
    public int $id;
    public string $name;
    public string $surname;
    public string $username;
    public string $email;
    public string $password;
    public UserRole $role;

    public function __construct(int $id = 0, string $name, string $surname, string $username, string $email, string $password, UserRole|string $role) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        //if role type is string, convert it to UserRole
        if (is_string($role)) {
            $this->role = UserRole::from($role);
        } else {
            $this->role = $role;
        }
    }

}
?>