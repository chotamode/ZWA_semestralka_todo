<?php

require_once '../Model/InvitationStatus.php';
require_once '../Model/TaskStatus.php';
require_once '../Model/Project.php';
require_once '../Model/Task.php';
require_once '../Model/User.php';
require_once '../Model/Invitation.php';
require_once '../Model/Role.php';

/**
 * Class Repository
 * 
 * This class is responsible for storing and retrieving data from the SQL database.
 */

class Repository {

    private static $instance;
    private PDO $connection;

    public function __construct() {

        $servername = "localhost";
        $port = "8889";
        $username = "root";
        $password = "password";

        $this->connection = new PDO("mysql:host=$servername;port=$port;dbname=todo_list", $username, $password);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Repository();
        }

        return self::$instance;
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

    public function updateUserPassword(int $id, string $password): void {
        $statement = $this->connection->prepare('UPDATE users SET password = :password WHERE id = :id');
        $statement->execute([
            'id' => $id,
            'password' => $password,
        ]);
    }

    // TASKS

    public function createTask(Task $task, int $userId ): int {
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

        return $taskId;
    }

    public function getTasksByUserId(int $userId): array {
        // in database there is table called users_tasks which has user_id and task_id columns

        $statement = $this->connection->prepare('SELECT * FROM users_tasks INNER JOIN tasks ON users_tasks.task_id = tasks.id WHERE users_tasks.user_id = :userId');
        $statement->execute([
            'userId' => $userId,
        ]);
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        $tasks = array_map(function($task) {
            return new Task($task['id'], $task['name'], $task['description'], new DateTime($task['deadline']), stringToTaskStatus($task['taskstatus']), $task['project_id']);
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

    public function getTasksByProjectId(int $projectId): array {
        $statement = $this->connection->prepare('SELECT * FROM project_tasks INNER JOIN tasks ON project_tasks.task_id = tasks.id WHERE project_tasks.project_id = :projectId');
        $statement->execute([
            'projectId' => $projectId,
        ]);
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

        $tasks = array_map(function($task) {
            return new Task($task['id'], $task['name'], $task['description'], new DateTime($task['deadline']), stringToTaskStatus($task['taskstatus']), $task['project_id']);
        }, $tasks);

        return $tasks;
    }

    /**
     * Updates all fields of a task with given id.
     * 
     * @param Task $task task with updated fields
     * @return void
     */
    public function updateTask(Task $task): void {
        $statement = $this->connection->prepare('UPDATE tasks SET name = :name, description = :description, deadline = :deadline, taskstatus = :taskStatus WHERE id = :id');
        $statement->execute([
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'deadline' => $task->deadline->format('Y-m-d'),
            'taskStatus' => $task->taskStatus->value,
        ]);
    }

    /**
     * Updates only name, description and deadline of a task with given id.
     * 
     * @param int $id id of a task
     * @param string $name new name
     * @param string $description new description
     * @param DateTime $deadline new deadline
     * @return void
     */
    public function updateTaskDetailsById(int $id, string $name, string $description, DateTime $deadline): void {
        $statement = $this->connection->prepare('UPDATE tasks SET name = :name, description = :description, deadline = :deadline WHERE id = :id');
        $statement->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'deadline' => $deadline->format('Y-m-d'),
        ]);
    }

    /**
     * Updates only task status of a task with given id.
     * 
     * @param int $id id of a task
     * @param TaskStatus $taskStatus new task status
     * @return void
     */
    public function updateTaskStatusById(int $id, TaskStatus $taskStatus): void {
        $statement = $this->connection->prepare('UPDATE tasks SET task_status = :taskStatus WHERE id = :id');
        $statement->execute([
            'id' => $id,
            'taskStatus' => $taskStatus->value,
        ]);
    }

    public function deleteTask(int $id): void {
        $statement = $this->connection->prepare('DELETE FROM users_tasks WHERE task_id = :id');
        $statement->execute([
            'id' => $id,
        ]);

        $statement = $this->connection->prepare('DELETE FROM project_tasks WHERE task_id = :id');
        $statement->execute([
            'id' => $id,
        ]);
    
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

    public function assignTaskToProject(int $taskId, int $projectId): void {
        if($projectId == 0) {
            $statement = $this->connection->prepare('UPDATE tasks SET project_id = NULL WHERE id = :taskId');
            $statement->execute([
                'taskId' => $taskId,
            ]);

            $statement = $this->connection->prepare('DELETE FROM project_tasks WHERE task_id = :taskId');
            $statement->execute([
                'taskId' => $taskId,
            ]);

            return;
        }

        $statement = $this->connection->prepare('INSERT INTO project_tasks (project_id, task_id) VALUES (:projectId, :taskId)');
        $statement->execute([
            'projectId' => $projectId,
            'taskId' => $taskId,
        ]);

        $statement = $this->connection->prepare('DELETE FROM project_tasks WHERE task_id = :taskId AND project_id != :projectId');
        $statement->execute([
            'projectId' => $projectId,
            'taskId' => $taskId,
        ]);

        $statement = $this->connection->prepare('UPDATE tasks SET project_id = :projectId WHERE id = :taskId');
        $statement->execute([
            'projectId' => $projectId,
            'taskId' => $taskId,
        ]);
    }

    // PROJECTS

    public function createProject(Project $project): void {
        $statement = $this->connection->prepare('INSERT INTO project (name) VALUES (:name)');
        $statement->execute([
            'name' => $project->name,
        ]);

        //insert relation to project_users table (many to many)
        $projectId = $this->connection->lastInsertId();
        $statement = $this->connection->prepare('INSERT INTO project_users (project_id, user_id) VALUES (:projectId, :userId)');
        foreach ($project->userIds as $userId) {
            $statement->execute([
                'projectId' => $projectId,
                'userId' => $userId,
            ]);
        }
    }

    public function updateProjectName(Project $project): void {
        $statement = $this->connection->prepare('UPDATE project SET name = :name WHERE id = :id');
        $statement->execute([
            'id' => $project->id,
            'name' => $project->name,
        ]);
    }

    public function deleteProject(int $id): void {
        $statement = $this->connection->prepare('DELETE FROM project_users WHERE project_id = :id');
        $statement->execute([
            'id' => $id,
        ]);

        $statement = $this->connection->prepare('DELETE FROM project WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
    }

    public function assignUserToProject(int $projectId, int $userId): void {
        $statement = $this->connection->prepare('INSERT INTO project_users (project_id, user_id) VALUES (:projectId, :userId)');
        $statement->execute([
            'projectId' => $projectId,
            'userId' => $userId,
        ]);
    }

    public function getprojectByUserId(int $userId): array {
        // in database there is table called project_users which has project_id and user_id columns

        $statement = $this->connection->prepare('SELECT * FROM project_users INNER JOIN project ON project_users.project_id = project.id WHERE project_users.user_id = :userId');
        $statement->execute([
            'userId' => $userId,
        ]);
        $project = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($project as $project) {
            $result[] = new Project($project['id'], $project['name'], $this->getUserIdsByProjectId($project['id']), $this->getTaskIdsByProjectId($project['id']));
        }

        return $result;
    }

    public function getProjectById(int $id): Project {
        $statement = $this->connection->prepare('SELECT * FROM project WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
        $project = $statement->fetch(PDO::FETCH_ASSOC);

        return new Project($project['id'], $project['name'], $this->getUserIdsByProjectId($project['id']), $this->getTaskIdsByProjectId($project['id']));
    }

    public function getUserIdsByProjectId(int $projectId): array {
        $statement = $this->connection->prepare('SELECT user_id FROM project_users WHERE project_id = :projectId');
        $statement->execute([
            'projectId' => $projectId,
        ]);
        $userIds = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($userIds as $userId) {
            $result[] = $userId['user_id'];
        }

        return $result;
    }

    public function getTaskIdsByProjectId(int $projectId): array {
        $statement = $this->connection->prepare('SELECT task_id FROM project_tasks WHERE project_id = :projectId');
        $statement->execute([
            'projectId' => $projectId,
        ]);
        $taskIds = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($taskIds as $taskId) {
            $result[] = $taskId['task_id'];
        }

        return $result;
    }

    public function getProjectsByUserId(int $userId): array {
        $statement = $this->connection->prepare('SELECT * FROM project_users INNER JOIN project ON project_users.project_id = project.id WHERE project_users.user_id = :userId');
        $statement->execute([
            'userId' => $userId,
        ]);
        $projects = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($projects as $project) {
            $result[] = new Project($project['id'], $project['name'], $this->getUserIdsByProjectId($project['id']), $this->getTaskIdsByProjectId($project['id']));
        }

        return $result;
    }

    // invitation

    public function createInvitation(Invitation $invitation): void {
        $statement = $this->connection->prepare('INSERT INTO invitation (project_id, sender_id, receiver_id, status, project_name) VALUES (:projectId, :senderId, :receiverId, :status, :projectName)');
        $statement->execute([
            'projectId' => $invitation->projectId,
            'senderId' => $invitation->senderId,
            'receiverId' => $invitation->receiverId,
            'status' => $invitation->invitationStatus->value,
            'projectName' => $invitation->projectName,
        ]);
    }

    public function createInvitationByUserName(int $projectId, string $userName): void {
        $user = $this->getUserByUsername($userName);
        $statement = $this->connection->prepare('INSERT INTO invitation (project_id, sender_id, receiver_id, status, project_name) VALUES (:projectId, :senderId, :receiverId, :status, :projectName)');
        $statement->execute([
            'projectId' => $projectId,
            'senderId' => $_COOKIE['user_id'],
            'receiverId' => $user->id,
            'status' => InvitationStatus::PENDING->value,
            'projectName' => $this->getProjectById($projectId)->name,
        ]);
    }

    public function updateInvitationStatus($invitationId, InvitationStatus $invitationStatus): void {
        $statement = $this->connection->prepare('UPDATE invitation SET status = :status WHERE id = :id');
        $statement->execute([
            'id' => $invitationId,
            'status' => $invitationStatus->value,
        ]);
    }

    public function deleteInvitation(int $id): void {
        $statement = $this->connection->prepare('DELETE FROM invitation WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
    }

    public function getSentInvitationsByUserId(int $userId): array {
        $statement = $this->connection->prepare('SELECT * FROM invitation WHERE sender_id = :userId');
        $statement->execute([
            'userId' => $userId,
        ]);
        $invitations = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($invitations as $invitation) {
            $result[] = new Invitation($invitation['id'], $invitation['project_id'], $invitation['sender_id'], $invitation['receiver_id'], stringToInvitationStatus($invitation['status']));
        }

        return $result;
    }

    public function getReceivedInvitationsByUserId(int $userId): array {
        $statement = $this->connection->prepare('SELECT * FROM invitation WHERE receiver_id = :userId');
        $statement->execute([
            'userId' => $userId,
        ]);
        $invitations = $statement->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($invitations as $invitation) {
            $result[] = new Invitation($invitation['id'], $invitation['project_id'], $invitation['sender_id'], $invitation['receiver_id'], stringToInvitationStatus($invitation['status']), $invitation['project_name']);
        }

        return $result;
    }
    
    public function getInvitationById(int $id): Invitation {
        $statement = $this->connection->prepare('SELECT * FROM invitation WHERE id = :id');
        $statement->execute([
            'id' => $id,
        ]);
        $invitation = $statement->fetch(PDO::FETCH_ASSOC);

        return new Invitation($invitation['id'], $invitation['project_id'], $invitation['sender_id'], $invitation['receiver_id'], stringToInvitationStatus($invitation['status']), $invitation['project_name']);
    }
}
?>