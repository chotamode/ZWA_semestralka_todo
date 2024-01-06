<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/tasks.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <script src="../JS/tasks.js"></script>
    <title>Tasks</title>
</head>

<body>
    <?php

    /**
     * tasks.php
     * 
     * This file represents a tasks page. It is used to display all tasks.
     * 
     * @category Pages
     * @package  Pages
     */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'Blocks/topnav.php';
    require_once '../Repository/Repository.php';
    require_once '../Model/Task.php';
    require_once '../Model/TaskStatus.php';
    require_once 'Blocks/task.php';
    require_once '../Service/TaskService.php';
    require_once '../Model/Project.php';
    require_once '../Service/ProjectService.php';

    $projectService = new ProjectService();

    $taskService = new TaskService();
    $tasks = $taskService->getTasksByUserId($_COOKIE['user_id']);

    $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);
    foreach ($projects as $project) {
        $projectTasks = $taskService->getTasksByProjectId($project->id);
        foreach ($projectTasks as $projectTask) {
            if (!in_array($projectTask, $tasks)) {
                array_push($tasks, $projectTask);
            }
        }
    }

    if (isset($_GET['project_id']) && $_GET['project_id'] != 0) {
        if ($_GET['project_id'] == 'personal') {
            $tasks = array_filter($tasks, function ($task) {
                return $task->projectId == null;
            });
        } else {
            $tasks = array_filter($tasks, function ($task) {
                return $task->projectId == $_GET['project_id'];
            });
        }
    }

    $todo = [];
    $doing = [];
    $done = [];

    foreach ($tasks as $task) {
        switch ($task->taskStatus) {
            case TaskStatus::TODO:
                array_push($todo, $task);
                break;
            case TaskStatus::DOING:
                array_push($doing, $task);
                break;
            case TaskStatus::DONE:
                array_push($done, $task);
                break;
        }
    }
    ?>

    <div id="sections_container">

        <div id="add_filter_container">
            <!-- add task -->
            <details>
                <summary>Add Task</summary>
                <div id="add_task_container">
                    <form action="../Controller/TaskController.php?action=create_task" method="POST">
                        <label for="name" class="add_task_label">Name</label>
                        <input type="text" name="name" id="name" class="add_task_input" required>

                        <label for="description" class="add_task_label">Description</label>
                        <textarea name="description" id="description" class="add_task_input" required></textarea>

                        <label for="deadline" class="add_task_label">Deadline</label>
                        <input type="date" name="deadline" id="deadline" class="add_task_input" required>

                        <label for="project"  class="add_task_label">Project</label>
                        <!-- project selector -->
                        <select name="project" id="project" class="add_task_select">
                            <option value="0">Personal</option>
                            <?php
                            $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);
                            foreach ($projects as $project) {
                                echo '<option value="' . $project->id . '">' . $project->name . '</option>';
                            }
                            ?>
                        </select>

                        <button type="submit" class="add_task_button" id="add_task">Add task</button>
                    </form>
                </div>
            </details>

            <!-- task filter selector -->
            <form action="tasks.php" method="GET" class="filter_form">
                <select name="project_id" id="project_id" class="project_filter_selector">
                    <option value="0">All</option>
                    <option value="personal">Personal</option>
                    <?php
                    $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);
                    foreach ($projects as $project) {
                        echo '<option value="' . $project->id . '">' . $project->name . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="filter_submit_button" id="filter_submit_button">Filter</button>
            </form>
        </div>

        <div id="todo_container" class="task_container">

            <h2>Todo</h2>

            <?php
            foreach ($todo as $task) {
                echo renderTask($task);
            }
            ?>

        </div>

        <div id="doing_container" class="task_container">

            <h2>Doing</h2>

            <?php
            foreach ($doing as $task) {
                echo renderTask($task);
            }
            ?>

        </div>

        <div id="done_container" class="task_container">

            <h2>Done</h2>

            <?php
            foreach ($done as $task) {
                echo renderTask($task);
            }
            ?>

        </div>

    </div>

</body>

</html>