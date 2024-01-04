<?php

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