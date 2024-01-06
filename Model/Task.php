<?php

/**
 * Task
 * 
 * This class is a model class representing a task. This class is used to implement tasks that users can work on.
 * 
 * @param int $id Task ID that is unique for each task in the database (auto-incremented)
 * @param string $name Task name
 * @param string $description Task description
 * @param DateTime $deadline Task deadline
 * @param TaskStatus $taskStatus Task status (TODO, DOING, DONE)
 * @param int $projectId Project ID that is unique for each project in the database foreign key to the project table
 * 
 * @category Model
 * @package  Model
 */

require_once 'TaskStatus.php';

class Task {
    public int $id;
    public string $name;
    public string $description;
    public DateTime $deadline;
    public TaskStatus $taskStatus;

    public ?int $projectId;

    public function __construct(int $id, string $name, string $description, DateTime $deadline, TaskStatus $taskStatus, int $projectId = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->taskStatus = $taskStatus;
        $this->projectId = $projectId;
    }
}

?>