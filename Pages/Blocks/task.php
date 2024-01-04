<?php

require_once '../Service/ProjectService.php';

function renderTask(Task $task)
{

    $projectService = new ProjectService();

    $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);

    // generating string for options in select
    $optionsString = '';
    foreach ($projects as $project) {
        // auto select if task.projectId == project.id
        $optionsString .= '<option value="' . $project->id . '" ' . ($task->projectId == $project->id ? 'selected' : '') . '>' . $project->name . '</option>';
    }

    return '
    <div class="task" id="task_' . $task->id . '">
        <form method="post" action="../Controller/TaskController.php?action=update_task&id=' . $task->id . '">

            <label for="task_' . $task->id . '_name">Name</label>
            <input type="text" class="task_input" name="name" id="task_' . $task->id . '_name" value="' . $task->name . '" required>

            <label for="task_' . $task->id . '_description">Description</label>
            <input type="text" class="task_input" name="description" id="task_' . $task->id . '_description" value="' . $task->description . '" required>

            <label for="task_' . $task->id . '_deadline">Deadline</label>
            <input type="date" class="task_input" name="deadline" id="task_' . $task->id . '_deadline" value="' . $task->deadline->format('Y-m-d') . '" required>

            <label for="task_' . $task->id . '_project"></label>
            <select name="project" id="task_' . $task->id . '_project">
                <option value="0">Personal</option>
                ' . $optionsString . '
            </select>

            <label for="task_' . $task->id . '_options"></label>
            <select name="taskStatus" id="task_' . $task->id . '_options">
                <option value="' . TaskStatus::TODO->value . '" ' . ($task->taskStatus->value == TaskStatus::TODO->value ? 'selected' : '') . '>Todo</option>
                <option value="' . TaskStatus::DOING->value . '" ' . ($task->taskStatus->value == TaskStatus::DOING->value ? 'selected' : '') . '>Doing</option>
                <option value="' . TaskStatus::DONE->value . '" ' . ($task->taskStatus->value == TaskStatus::DONE->value ? 'selected' : '') . '>Done</option>
            </select>

            <button type="submit">Update</button>
            
        </form>
        <form method="post" action="../Controller/TaskController.php?action=delete_task&id=' . $task->id . '">
            <button type="submit">Delete</button>
        </form>
    </div>';
}
