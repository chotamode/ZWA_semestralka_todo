<?php

/**
 * TaskController
 * 
 * This controller is responsible for handling all task related requests.
 * 
 * @category Controllers
 * @package Controller
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Service/TaskService.php');

$taskService = new TaskService();

/**
 * Switch statement to handle different actions
 * 
 * @param string $_GET['action'] Action to be performed
 * @param string $_POST['name'] Name
 * @param string $_POST['description'] Description
 * @param string $_POST['deadline'] Deadline
 * @param string $_COOKIE['user_id'] User ID
 * @param string $_POST['project'] Project ID
 * @param string $_POST['taskStatus'] Task status
 * @param string $_POST['name'] Name
 * @param string $_POST['description'] Description
 * @param string $_POST['deadline'] Deadline
 * @param string $_POST['taskStatus'] Task status
 * @param string $_GET['id'] Task ID
 */
switch ($_GET['action']) {
    case 'create_task':
        $taskId = $taskService->createTask($_POST['name'], $_POST['description'], $_POST['deadline'], $_COOKIE['user_id']);
        if (isset($_POST['project']) && $_POST['project'] != 0) {
            $taskService->assignTaskToProject($taskId, $_POST['project']);
        }
        break;

    case 'update_task':
        $taskService->updateTask($_GET['id'], $_POST['name'], $_POST['description'], $_POST['deadline'], $_POST['taskStatus']);
        if (isset($_POST['project'])) {
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

/**
 * Check if request is AJAX or not and return JSON if it is, otherwise redirect to tasks page
 */
isset($_POST['ajax']) && $_POST['ajax'] == 'true' ? header('Content-Type: application/json') : header('Location: ../Pages/tasks.php');
