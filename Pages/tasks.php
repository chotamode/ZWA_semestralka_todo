<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/tasks.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <title>Tasks</title>
</head>

<body>
    <?php

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
            if(!in_array($projectTask, $tasks)) {
                array_push($tasks, $projectTask);
            }
        }
    }

    if(isset($_GET['project_id']) && $_GET['project_id'] != 0) {
        if($_GET['project_id'] == 'personal') {
            $tasks = array_filter($tasks, function($task) {
                return $task->projectId == null;
            });
        } else {
            $tasks = array_filter($tasks, function($task) {
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

    <!-- task filter selector -->
    <form action="tasks.php" method="GET">
        <select name="project_id" id="project_id">
            <option value="0">All</option>
            <option value="personal">Personal</option>
            <?php
            $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);
            foreach ($projects as $project) {
                echo '<option value="' . $project->id . '">' . $project->name . '</option>';
            }
            ?>
        </select>
        <button type="submit">Filter</button>
    </form>

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

    <!-- add task -->
    <div id="add_task_container">
        <form action="../Controller/TaskController.php?action=create_task" method="POST">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Description</label>
            <input type="text" name="description" id="description" required>

            <label for="deadline">Deadline</label>
            <input type="date" name="deadline" id="deadline" required>

            <label for="project">Project</label>
            <!-- project selector -->
            <select name="project" id="project">
                <option value="0">Personal</option>
                <?php
                $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);
                foreach ($projects as $project) {
                    echo '<option value="' . $project->id . '">' . $project->name . '</option>';
                }
                ?>
            </select>

            <button type="submit">Add task</button>
        </form>
    </div>

</body>

</html>