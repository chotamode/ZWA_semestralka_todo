<?php

require_once '../Repository/Repository.php';
require_once '../Model/Project.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProjectService
{

    private $repository;

    public function __construct()
    {
        $this->repository = Repository::getInstance();
    }

    public function createProject($name, $userIds)
    {
        $project = new Project(0, $name, $userIds, []);
        $this->repository->createProject($project);
    }

    public function updateProjectName($id, $name)
    {
        $project = new Project($id, $name, [], []);
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
}
