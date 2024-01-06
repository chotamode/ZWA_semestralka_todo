## Description
This is a documentation for the programmer of the project. It contains information about the project structure, used technologies, and other useful information.

## Project Structure

### Packages
* Repository - contains the repository classs responsible for communication with the database
* Model - contains the model classes representing the database tables entities
* Controller - contains the controller classes responsible for handling requests and responses
* Service - contains the service classes responsible for performing business logic
* Pages - contains pages writen in PHP HTML
* Pages/Blocks - contains blocks of HTML code used in the pages. For example, task.php contains the HTML code for displaying a task. This code is used in the task list page.
* Images - contains images used in the project
* CSS - contains CSS files used in the project
* JS - contains JavaScript files used in the project
* UserImages - contains images uploaded by users and managed by the application

### Description
The project is written in PHP. The project uses the MVC design pattern. The project is divided into packages. Each package contains classes with a specific purpose. The packages are described in the previous section.

Controllers are responsible for handling requests and responses. Each controller class handles a specific type of request. For example, the TaskController class handles requests related to tasks. The controller classes are located in the Controller package. Controller use services to perform business logic. The services are located in the Service package. Services use repositories to communicate with the database. The repositories are located in the Repository package. The repositories use model classes to represent the database entities. The model classes are located in the Model package.
