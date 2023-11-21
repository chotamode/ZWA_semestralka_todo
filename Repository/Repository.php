<?php

/**
 * Class Repository
 * 
 * This class is responsible for storing and retrieving data from the SQL database.
 */

class Repository {
    private PDO $connection;

    public function __construct() {

        $servername = "localhost";
        $port = "8889";
        $username = "root";
        $password = "password";

        $this->connection = new PDO("mysql:host=$servername;port=$port;dbname=todo_list", $username, $password);
    }

    public function getAllUsers(): array {
        $statement = $this->connection->prepare('SELECT * FROM users');
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($users as $user) {
            $result[] = new User($user['id'], $user['name'], $user['surname'], $user['username'], $user['email'], $user['password'], $user['UserRole']);
        }

        return $result;
    }

    public function createUser(User $user): void {
        $statement = $this->connection->prepare('INSERT INTO users (name, surname, username, email, password, UserRole) VALUES (:name, :surname, :username, :email, :password, :userRole)');
        $statement->execute([
            'name' => $user->name,
            'surname' => $user->surname,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'userRole' => $user->role->value,
        ]);
    }

    public function getAllTasks(): array {
        $statement = $this->connection->prepare('SELECT * FROM tasks');
        $statement->execute();
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($tasks as $task) {
            $result[] = new Task($task['id'], $task['name'], $task['description'], new DateTime($task['deadline']), explode(',', $task['user_ids']));
        }

        return $result;
    }
}
?>