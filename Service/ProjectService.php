<?php

require_once '../Repository/Repository.php';
require_once '../Model/Project.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * ProjectService
 * 
 * This service is responsible for handling all project related requests. Gives another layer of abstraction between the controller and the repository.
 * 
 * @category   Services
 * @package    Service
 */

class ProjectService
{

    private $repository;

    public function __construct()
    {
        $this->repository = Repository::getInstance();
    }

    public function createProject($name, $userIds, $ownerId)
    {
        $project = new Project(0, $name, $userIds, [], $ownerId);
        $this->repository->createProject($project);
    }

    public function updateProjectName($id, $name, $ownerId)
    {
        $project = new Project($id, $name, [], [], $ownerId);
        $this->repository->updateProjectName($project);
    }

    public function deleteProject($id)
    {
        $this->repository->deleteProject($id);
    }

    public function getProjectsByUserId($userId)
    {
        return $this->repository->getProjectsByUserId($userId);
    }

    public function removeUserFromProject($projectId, $userId)
    {
        $this->repository->removeUserFromProject($projectId, $userId);
    }

    public function isUserOwnerOfProject($projectId, $userId)
    {
        $project = $this->repository->getProjectById($projectId);
        return $project->ownerId == $userId;
    }

    // INVITATIONS

    public function inviteUserToProject($projectId)
    {
        $this->repository->createInvitation($projectId);
    }

    public function inviteUserToProjectByUserName($projectId, $userName)
    {
        $this->repository->createInvitationByUserName($projectId, $userName);
    }

    public function acceptInvitation($invitationId)
    {
        $this->repository->updateInvitationStatus($invitationId, InvitationStatus::ACCEPTED);

        $invitation = $this->repository->getInvitationById($invitationId);
        $this->assignUserToProject($invitation->projectId, $invitation->receiverId);
    }

    public function declineInvitation($invitationId)
    {
        $this->repository->updateInvitationStatus($invitationId, InvitationStatus::DECLINED);
    }

    public function getSentInvitationsByUserId($userId)
    {
        return $this->repository->getSentInvitationsByUserId($userId);
    }

    public function getReceivedInvitationsByUserId($userId)
    {
        return $this->repository->getReceivedInvitationsByUserId($userId);
    }

    public function assignUserToProject($projectId, $userId)
    {
        $this->repository->assignUserToProject($projectId, $userId);
    }

    public function getSenderUsernameByInvitationId($invitationId)
    {
        $senderId = $this->repository->getInvitationById($invitationId)->senderId;
        return $this->repository->getUserById($senderId)->username;
    }

    public function getReceiverUsernameByInvitationId($invitationId)
    {
        $receiverId = $this->repository->getInvitationById($invitationId)->receiverId;
        return $this->repository->getUserById($receiverId)->username;
    }

    public function checkIfUserIsAssignedToProject($projectId, $username)
    {
        $project = $this->repository->getProjectById($projectId);
        $users = $project->userIds;
        foreach ($users as $userId) {
            $user = $this->repository->getUserById($userId);
            if ($user->username == $username) {
                return true;
            }
        }
        return false;
    }

    public function checkIfUserIsInvitedToProject($projectId, $username)
    {
        $invitations = $this->repository->getSentInvitationsByProjectId($projectId);
        $userId = $this->repository->getUserByUsername($username)->id;
        foreach ($invitations as $invitation) {
            if ($invitation->receiverId == $userId && $invitation->invitationStatus == InvitationStatus::PENDING) {
                return false;
            }
        }
        return true;

    }

    /**
     * Cancel sent invitation
     * 
     * @param int $invitationId Invitation ID
     */
    public function cancelInvitation($invitationId)
    {
        $this->repository->updateInvitationStatus($invitationId, InvitationStatus::DECLINED);
    }
}
