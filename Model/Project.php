<?php

class Project {
    public int $id;
    public string $name;
    public array $userIds;
    public array $taskIds;

    public function __construct(int $id, string $name, array $userIds, array $taskIds) {
        $this->id = $id;
        $this->name = $name;
        $this->userIds = $userIds;
        $this->taskIds = $taskIds;
    }
}

?>