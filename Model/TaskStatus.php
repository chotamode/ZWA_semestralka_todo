<?php

/**
 * TaskStatus
 * 
 * This is an enum class representing a task status.
 * 
 * @category Model
 * @package  Model
 */

enum TaskStatus: string{
    case TODO = 'TODO';
    case DOING = 'DOING';
    case DONE = 'DONE';
}

function stringToTaskStatus(string $taskStatus): TaskStatus {
    switch($taskStatus) {
        case 'TODO':
            return TaskStatus::TODO;
        case 'DOING':
            return TaskStatus::DOING;
        case 'DONE':
            return TaskStatus::DONE;
        default:
            throw new Exception('Invalid task status');
    }
}

?>