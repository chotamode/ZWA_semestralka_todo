<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
</head>

<body>

    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'Blocks/topnav.php';

    ?>

    <!-- Creating project form -->

    <form action="../Controller/ProjectController.php?action=create&user_id=<?php echo $_COOKIE['user_id'] ?>" method="POST">
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

    foreach ($projects as $project) {
        echo '<h2>' . $project->name . '</h2>';
        // Invite to project form by username
        echo '<form action="../Controller/ProjectController.php?action=invite&project_id=' . $project->id . '" method="POST">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                    <input type="submit" value="Invite">
                </form>';
        // Displaying project members
        echo '<h3>Members</h3>';
        foreach ($project->userIds as $userId) {
            $user = $authService->getUserById($userId);
            echo '<p>' . $user->username . '</p>';
        }
    }

    // displaying sent invitations
    echo '<h2>Sent invitations</h2>';
    $sentInvitations = $projectService->getSentInvitationsByUserId($_COOKIE['user_id']);
    foreach ($sentInvitations as $invitation) {
        echo '<p>' . $invitation->projectName . '</p>';
    }

    // displaying received invitations
    echo '<h2>Received invitations</h2>';
    $receivedInvitations = $projectService->getReceivedInvitationsByUserId($_COOKIE['user_id']);
    foreach ($receivedInvitations as $invitation) {
        echo '<p>' . $invitation->projectName . '</p>';
        echo '<form action="../Controller/ProjectController.php?action=accept_invitation&invitation_id=' . $invitation->id . '" method="POST">
                    <input type="submit" value="Accept">
                </form>';
        echo '<form action="../Controller/ProjectController.php?action=decline_invitation&invitation_id=' . $invitation->id . '" method="POST">
                    <input type="submit" value="Decline">
                </form>';
    }


    ?>


</body>

</html>