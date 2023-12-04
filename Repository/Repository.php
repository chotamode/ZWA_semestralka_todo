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

    public function getUserById(int $id): User {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return new User($user['id'], $user['name'], $user['surname'], $user['username'], $user['email'], $user['password'], $user['UserRole']);
    }

    public function getUserByUsername(string $username): User {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE username = :username');
        $statement->execute([
            'username' => $username,
        ]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return new User($user['id'], $user['name'], $user['surname'], $user['username'], $user['email'], $user['password'], $user['UserRole']);
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

    public function updateUser(User $user): void {
        $statement = $this->connection->prepare('UPDATE users SET name = :name, surname = :surname, username = :username, email = :email, password = :password, UserRole = :userRole WHERE id = :id');
        $statement->execute([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'userRole' => $user->role->value,
        ]);
    }

    public function deleteUser(int $id): void {
        $statement = $this->connection->prepare('DELETE FROM users WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
    }

    public function createTask(Task $task, int $userId ): void {
        $statement = $this->connection->prepare('INSERT INTO tasks (name, description, deadline, taskstatus) VALUES (:name, :description, :deadline, :taskStatus)');
        $statement->execute([
            'name' => $task->name,
            'description' => $task->description,
            'deadline' => $task->deadline->format('Y-m-d'),
            'taskStatus' => $task->taskStatus->value,
        ]);

        //insert relation to users_tasks table
        $taskId = $this->connection->lastInsertId();
        $statement = $this->connection->prepare('INSERT INTO users_tasks (user_id, task_id) VALUES (:userId, :taskId)');
        $statement->execute([
            'userId' => $userId,
            'taskId' => $taskId,
        ]);
    }

    public function getTasksByUserId(int $userId): array {
        // in database there is table called users_tasks which has user_id and task_id columns

        $statement = $this->connection->prepare('SELECT * FROM users_tasks INNER JOIN tasks ON users_tasks.task_id = tasks.id WHERE users_tasks.user_id = :userId');
        $statement->execute([
            'userId' => $userId,
        ]);
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        $tasks = array_map(function($task) {
            return new Task($task['id'], $task['name'], $task['description'], new DateTime($task['deadline']), stringToTaskStatus($task['taskstatus']));
        }, $tasks);

        return $tasks;
    }

    public function getTaskById(int $id): Task {
        $statement = $this->connection->prepare('SELECT * FROM tasks WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
        $task = $statement->fetch(PDO::FETCH_ASSOC);

        return new Task($task['id'], $task['name'], $task['description'], new DateTime($task['deadline']), stringToTaskStatus($task['taskstatus']));
    }

    public function updateTask(Task $task): void {
        $statement = $this->connection->prepare('UPDATE tasks SET name = :name, description = :description, deadline = :deadline, task_status = :taskStatus WHERE id = :id');
        $statement->execute([
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'deadline' => $task->deadline->format('Y-m-d'),
            'taskStatus' => $task->taskStatus->value,
        ]);
    }

    public function deleteTask(int $id): void {
        $statement = $this->connection->prepare('DELETE FROM tasks WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
    }

    public function getUsernamesByTaskId(int $taskId): array {
        $statement = $this->connection->prepare('SELECT users.username FROM users INNER JOIN users_tasks ON users.id = users_tasks.user_id WHERE users_tasks.task_id = :taskId');
        $statement->execute([
            'taskId' => $taskId,
        ]);
        $usernames = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($usernames as $username) {
            $result[] = $username['username'];
        }

        return $result;
    }
}
?>