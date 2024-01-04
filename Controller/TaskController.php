<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    require_once('../Service/TaskService.php');

    $taskService = new TaskService();

    switch ($_GET['action']) {
        case 'create_task':
            $taskId = $taskService->createTask($_POST['name'], $_POST['description'], $_POST['deadline'], $_COOKIE['user_id']);
            if(isset($_POST['project']) && $_POST['project'] != 0) {
                $taskService->assignTaskToProject($taskId, $_POST['project']);
            }
            break;

        case 'update_task':
            $taskService->updateTask($_GET['id'], $_POST['name'], $_POST['description'], $_POST['deadline'], $_POST['taskStatus']);
            if(isset($_POST['project'])) {
                $taskService->assignTaskToProject($_GET['id'], $_POST['project']);
            }
            break;

        case 'update_task_details':
            $taskService->updateTaskDetailsById($_GET['id'], $_POST['name'], $_POST['description'], $_POST['deadline']);
            break;

        case 'update_task_status':
            $taskService->updateTaskStatusById($_GET['id'], $_POST['taskStatus']);
            break;
        case 'delete_task':
            $taskService->deleteTask($_GET['id']);
            break;
    }

    header('Location: ../Pages/tasks.php');

?>