

/**
 * @description This file contains JavaScript code for managing task status updates in a task management system.
 * The code listens for the 'DOMContentLoaded' event and attaches event listeners to task status selectors.
 * When a task status is changed, an AJAX request is sent to update the task status on the server.
 * The updated task element is then moved to the corresponding container based on the selected option.
 */
document.addEventListener('DOMContentLoaded', function() {
    var taskStatusSelectors = [];
    taskStatusSelectors = document.getElementsByClassName('taskStatus');

    var todoContainer = document.getElementById('todo_container');
    var doingContainer = document.getElementById('doing_container');
    var doneContainer = document.getElementById('done_container');
    
    Array.from(taskStatusSelectors).forEach(selector => {
        selector.addEventListener('change', function() {
            var taskId = this.id.split('_')[1];
            var selectedOption = this.value;
            var data = new FormData();
            data.append('taskStatus', selectedOption);
            data.append('ajax', true);
            var request = new XMLHttpRequest();
            request.open('POST', '../Controller/TaskController.php?action=update_task_status&id=' + taskId, true);
            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {
                    var taskElement = document.getElementById('task_' + taskId);
                    switch(selectedOption) {
                        case 'TODO':
                            todoContainer.appendChild(taskElement);
                            break;
                        case 'DOING':
                            doingContainer.appendChild(taskElement);
                            break;
                        case 'DONE':
                            doneContainer.appendChild(taskElement);
                            break;
                    }
                } else {
                    console.error('Request failed');
                }
            };
            request.send(data);
        })
    })
})