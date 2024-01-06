<?php

/**
 * ProjectController
 * 
 * This controller is responsible for handling all project related requests.
 * 
 * 
 * @category   Controllers
 * @package    Controller
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Service/ProjectService.php');
require_once('../Service/AuthService.php');

$projectService = new ProjectService();
$authService = new AuthService();

/**
 * Switch statement to handle different actions
 * 
 * @param string $_GET['action'] Action to be performed
 * @param string $_POST['name'] Name
 * @param int $_GET['id'] Project ID
 * @param int $_GET['user_id'] User ID
 * @param string $_POST['username'] Username
 * @param int $_GET['invitation_id'] Invitation ID
 * @param int $_GET['project_id'] Project ID
 * 
 */
switch ($_GET['action']) {
    case 'create':
        if (isset($_GET['user_id'])) {
            $projectService->createProject($_POST['name'], array($_GET['user_id']), $_COOKIE['user_id']);
            header('Location: ../Pages/projects.php?message=project_created');
            break;
        } else {
            $projectService->createProject($_POST['name'], [], $_COOKIE['user_id']);
            header('Location: ../Pages/projects.php?message=project_created');
            break;
        }
    case 'update':
        if ($projectService->isUserOwnerOfProject($_GET['id'], $_COOKIE['user_id'])) {
            $projectService->updateProjectName($_GET['id'], $_POST['name'], $_COOKIE['user_id']);
            header('Location: ../Pages/projects.php?message=project_updated');
            break;
        } else {
            header('Location: ../Pages/projects.php?error=not_owner');
            break;
        }

    case 'delete':
        if ($projectService->isUserOwnerOfProject($_GET['id'], $_COOKIE['user_id'])) {
            $projectService->deleteProject($_GET['id']);
            header('Location: ../Pages/projects.php?message=project_deleted');
            break;
        } else {
            header('Location: ../Pages/projects.php?error=not_owner');
            break;
        }
    case 'invite':
        if ($projectService->isUserOwnerOfProject($_GET['project_id'], $_COOKIE['user_id'])) {
            if ($projectService->checkIfUserIsAssignedToProject($_GET['project_id'], $_POST['username'])) {
                header('Location: ../Pages/projects.php?error=user_already_assigned');
                break;
            } elseif (!$projectService->checkIfUserIsInvitedToProject($_GET['project_id'], $_POST['username'])) {
                header('Location: ../Pages/projects.php?error=user_already_invited');
                break;
            } elseif ($authService->getUserByUsername($_POST['username']) == null) {
                header('Location: ../Pages/projects.php?error=user_not_found');
                break;
            }
            $projectService->inviteUserToProjectByUserName($_GET['project_id'], $_POST['username']);
            header('Location: ../Pages/projects.php?message=invitation_sent');
            break;
        } else {
            header('Location: ../Pages/projects.php?error=not_owner');
            break;
        }
    case 'accept_invitation':
        $projectService->acceptInvitation($_GET['invitation_id']);
        header('Location: ../Pages/projects.php?message=invitation_accepted');
        break;
    case 'decline_invitation':
        $projectService->declineInvitation($_GET['invitation_id']);
        header('Location: ../Pages/projects.php?message=invitation_declined');
        break;
    case 'remove_user':
        if ($projectService->isUserOwnerOfProject($_GET['project_id'], $_COOKIE['user_id'])) {
            $projectService->removeUserFromProject($_GET['project_id'], $_GET['user_id']);
            header('Location: ../Pages/projects.php?message=user_removed');
            break;
        } else {
            header('Location: ../Pages/projects.php?error=not_owner');
            break;
        }
    case 'cancel_invitation':
        if ($projectService->isUserOwnerOfProject($_GET['project_id'], $_COOKIE['user_id'])) {
            $projectService->cancelInvitation($_GET['invitation_id']);
            header('Location: ../Pages/projects.php?message=invitation_canceled');
            break;
        } else {
            header('Location: ../Pages/projects.php?error=not_owner');
            break;
        }
    default:
        header('Location: ../Pages/projects.php');
        break;
}
