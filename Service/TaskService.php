<?php

require_once('../Repository/Repository.php');
require_once('../Model/Task.php');
require_once('../Model/TaskStatus.php');

class TaskService
{
    private $repository;

    public function __construct()
    {
        $this->repository = Repository::getInstance();
    }
    public function getTasksByUserId($userId)
    {
        return $this->repository->getTasksByUserId($userId);
    }
    public function createTask($name, $description, $deadline, $userId)
    {
        $task = new Task(0, $name, $description, new DateTime($deadline), TaskStatus::TODO);
        return $this->repository->createTask($task, $userId);
    }

    public function assignTaskToProject($taskId, $projectId)
    {
        $this->repository->assignTaskToProject($taskId, $projectId);
    }

    public function updateTask($id, $name, $description, $deadline, $taskStatus)
    {
        $task = new Task($id, $name, $description, new DateTime($deadline), TaskStatus::from($taskStatus));
        $this->repository->updateTask($task);
    }

    public function updateTaskDetailsById($id, $name, $description, $deadline)
    {
        $this->repository->updateTaskDetailsById($id, $name, $description, new DateTime($deadline));
    }

    public function updateTaskStatusById($id, $taskStatus)
    {
        $this->repository->updateTaskStatusById($id, TaskStatus::from($taskStatus));
    }

    public function deleteTask($id)
    {
        $this->repository->deleteTask($id);
    }

    public function getTasksByProjectId($projectId)
    {
        return $this->repository->getTasksByProjectId($projectId);
    }
}
