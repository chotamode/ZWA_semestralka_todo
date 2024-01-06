<?php

/**
 * Project
 * 
 * This is a model class representing a project. This class is used to implement users coworking on tasks.
 * 
 * @param int $id Project ID that is unique for each project in the database (auto-incremented)
 * @param string $name Project name
 * @param array $userIds Array of user IDs that are unique for each user in the database foreign key to the users table
 * @param array $taskIds Array of task IDs that are unique for each task in the database foreign key to the tasks table
 * @param int $ownerId User ID that is unique for each user in the database foreign key to the users table
 * 
 * @category Model
 * @package  Model
 */

class Project
{
    public int $id;
    public string $name;
    public array $userIds;
    public array $taskIds;

    public int $ownerId;

    public function __construct(int $id, string $name, array $userIds, array $taskIds, int $ownerId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->userIds = $userIds;
        $this->taskIds = $taskIds;
        $this->ownerId = $ownerId;
    }
}
