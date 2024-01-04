<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    require_once('../Service/ProjectService.php');

    $projectService = new ProjectService();

    switch ($_GET['action']) {
        case 'create':
            if(isset($_GET['user_id'])) {
                $projectService->createProject($_POST['name'], array($_GET['user_id']));
                break;
            } else {
                $projectService->createProject($_POST['name'], []);
                break;
            }
        case 'update':
            $projectService->updateProjectName($_GET['id'], $_POST['name']);
            break;

        case 'delete':
            $projectService->deleteProject($_GET['id']);
            break;
        case 'invite':
            $projectService->inviteUserToProjectByUserName($_GET['project_id'], $_POST['username']);
            break;
        case 'accept_invitation':
            $projectService->acceptInvitation($_GET['invitation_id']);
            break;
        case 'decline_invitation':
            $projectService->declineInvitation($_GET['invitation_id']);
            break;
    }

    header('Location: ../Pages/projects.php');

?>