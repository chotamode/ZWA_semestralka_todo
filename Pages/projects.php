<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/topnav.css">
    <link rel="stylesheet" href="../CSS/projects.css">
    <title>Projects</title>
</head>

<body>

    <?php

    /**
     * projects.php
     * 
     * This file represents a projects page. It is used to display all projects, create new ones, update and delete them.
     * Also it is used to invite users to projects and accept or decline invitations.
     * Projects give users ability to collaborate on tasks.
     * 
     * @category Pages
     * @package  Pages
     */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'Blocks/topnav.php';

    ?>

    <!-- Creating project form -->

    <form action="../Controller/ProjectController.php?action=create&user_id=<?php echo $_COOKIE['user_id'] ?>" method="POST" id="project_create_form">
        <label for="name">Project name</label>
        <input type="text" name="name" id="name" required>

        <input type="submit" value="Create">
    </form>

    <!-- Displaying projects names-->

    <?php

    require_once '../Repository/Repository.php';
    require_once '../Model/Project.php';
    require_once '../Service/ProjectService.php';
    require_once '../Service/AuthService.php';
    require_once '../Model/Invitation.php';

    $authService = new AuthService();

    $projectService = new ProjectService();
    $projects = $projectService->getProjectsByUserId($_COOKIE['user_id']);

    echo '<div class="projects_container">';
    foreach ($projects as $project) {
        echo '<div class="project_container">';
        echo '<h2>' . htmlspecialchars($project->name) . '</h2>';

        if ($project->ownerId == $_COOKIE['user_id']) {
            echo '<form action="../Controller/ProjectController.php?action=delete&id=' . $project->id . '" method="POST">
                        <button type="submit" class="delete_button"></button>
                    </form>';
            echo '<form action="../Controller/ProjectController.php?action=update&id=' . $project->id . '" method="POST" id="project_update_form">
                    <label for="name">Project name</label>
                    <input type="text" name="name" id="name" required>
                    <input type="submit" value="Update">
                </form>';
            echo '<form action="../Controller/ProjectController.php?action=invite&project_id=' . $project->id . '" method="POST" id="project_invite_form">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
                <input type="submit" value="Invite">
            </form>';
        }

        /**
         * Displaying members
         */
        echo '<h3>Members</h3>';
        echo '<div class="members_container">';
        foreach ($project->userIds as $userId) {
            $user = $authService->getUserById($userId);
            echo '<div class="member_container">';
            echo '<p>' . htmlspecialchars($user->username) . '</p>';
            if ($project->ownerId == $_COOKIE['user_id']) {
                echo '<form action="../Controller/ProjectController.php?action=remove_user&project_id=' . $project->id . '&user_id=' . $userId . '" method="POST">
                            <input type="submit" value="X">
                        </form>';
            }
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';

    /**
     * Displaying sent invitations
     */
    echo '<div class="sent_invitations_container">';
    echo '<h2>Sent invitations</h2>';
    $sentInvitations = $projectService->getSentInvitationsByUserId($_COOKIE['user_id']);
    echo '<table>';
    echo '<th>Project name</th>';
    echo '<th>Receiver</th>';
    echo '<th>Status</th>';
    echo '<th>Cancel</th>';
    foreach ($sentInvitations as $invitation) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($invitation->projectName) . '</td>';
        echo '<td>' . htmlspecialchars($projectService->getReceiverUsernameByInvitationId($invitation->id) ) . '</td>';
        echo '<td>' . htmlspecialchars($invitation->invitationStatus->value) . '</td>';
        if($invitation->invitationStatus == InvitationStatus::PENDING) {
            echo '<td>
                    <form action="../Controller/ProjectController.php?action=cancel_invitation&invitation_id=' . $invitation->id . '&project_id=' . $invitation->projectId . '" method="POST">
                        <input type="submit" value="Cancel" class="cancel_button">
                    </form>
                </td>';
        }else{
            echo '<td></td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';


    /**
     * Displaying received invitations
     */
    echo '<div class="received_invitations_container">';
    echo '<h2>Received invitations</h2>';
    $receivedInvitations = $projectService->getReceivedInvitationsByUserId($_COOKIE['user_id']);
    echo '<table>';
    echo '<th>Project name</th>';
    echo '<th>Sender</th>';
    echo '<th>Status</th>';
    echo '<th>Accept</th>';
    echo '<th>Decline</th>';
    foreach ($receivedInvitations as $invitation) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($invitation->projectName) . '</td>';
        echo '<td>' . htmlspecialchars($projectService->getSenderUsernameByInvitationId($invitation->id) ) . '</td>';
        echo '<td>' . htmlspecialchars($invitation->invitationStatus->value) . '</td>';
        if($invitation->invitationStatus == InvitationStatus::PENDING) {
            echo '<td>
                    <form action="../Controller/ProjectController.php?action=accept_invitation&invitation_id=' . $invitation->id . '" method="POST">
                        <input type="submit" value="Accept" class="accept_button">
                    </form>
                </td>';
            echo '<td>
                    <form action="../Controller/ProjectController.php?action=decline_invitation&invitation_id=' . $invitation->id . '" method="POST">
                        <input type="submit" value="Decline" class="decline_button">
                    </form>
                </td>';
        }else{
            echo '<td></td>';
            echo '<td></td>';
        }
        echo '</tr>';
    }
    echo '</div>';

    require_once 'Blocks/notification.php';
    if (isset($_GET['message'])) {
        renderNotification($_GET['message']);
    } elseif (isset($_GET['error'])) {
        renderNotification($_GET['error']);
    }

    ?>

</body>

</html>