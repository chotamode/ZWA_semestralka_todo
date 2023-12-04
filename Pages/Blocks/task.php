<?php
function generateTaskHTML(Task $task) {
    return '
    <div class="task" id="task_' . $task->id . '">
        <h3>' . $task->name . '</h3>
        <p>' . $task->description . '</p>
        <p>' . $task->deadline->format('Y-m-d') . '</p>

        <label for="task_' . $task->id . '_options"></label>
        <select name="task_' . $task->id . '_options" id="task_' . $task->id . '_options">
            <option value="todo" ' . ($task->taskStatus == 'todo' ? 'selected' : '') . '>Todo</option>
            <option value="doing" ' . ($task->taskStatus == 'doing' ? 'selected' : '') . '>Doing</option>
            <option value="done" ' . ($task->taskStatus == 'done' ? 'selected' : '') . '>Done</option>
        </select>
    </div>';
}
?>