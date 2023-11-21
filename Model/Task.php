<?php

class Task {
    public int $id;
    public string $name;
    public string $description;
    public DateTime $deadline;
    public array $userIds;

    public function __construct(int $id, string $name, string $description, DateTime $deadline, array $userIds) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->userIds = $userIds;
    }
}

?>