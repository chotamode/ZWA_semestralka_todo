<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../CSS/tasks.css">
        <title>Tasks</title>
    </head>

    <body>
        <?php 

            ini_set('display_errors', 1); 
            ini_set('display_startup_errors', 1); 
            error_reporting(E_ALL);

            require_once 'Blocks/topnav.php' ;
            require_once '../Repository/Repository.php';
            require_once '../Model/Task.php';
            require_once '../Model/TaskStatus.php';
            require_once 'Blocks/task.php';

            $repository = new Repository();
            $tasks = $repository->getTasksByUserId($_COOKIE['user_id']);

            $todo = [];
            $doing = [];
            $done = [];

            foreach($tasks as $task) {
                switch($task->taskStatus) {
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

        <div id="todo_container" class="task_container">

            <h2>Todo</h2>
            
            <?php
                foreach($todo as $task) {
                    echo generateTaskHTML($task);
                }
            ?>

        </div>

        <div id="doing_container" class="task_container">
            
            <h2>Doing</h2>

            <?php
                foreach($doing as $task) {
                    echo generateTaskHTML($task);
                }
            ?>

        </div>

        <div id="done_container" class="task_container">

            <h2>Done</h2>

            <?php
                foreach($done as $task) {
                    echo generateTaskHTML($task);
                }
            ?>

        </div>

        <!-- add task -->
        <div id="add_task_container">
            <form action="../Service/add_task.php" method="POST">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>

                <label for="description">Description</label>
                <input type="text" name="description" id="description" required>

                <label for="deadline">Deadline</label>
                <input type="date" name="deadline" id="deadline" required>

                <input type="submit" value="Add">
            </form>
        </div>

    </body>
</html>
