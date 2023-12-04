<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

require_once '../Model/Task.php';
require_once '../Model/TaskStatus.php';
require_once '../Repository/Repository.php';

//create task from form data
$task = new Task(0, $_POST['name'], $_POST['description'], new DateTime($_POST['deadline']), TaskStatus::TODO);

$repository = new Repository();
// add task to database
$repository->createTask($task, $_COOKIE['user_id']);
?>