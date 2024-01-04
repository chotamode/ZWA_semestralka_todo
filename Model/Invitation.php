<?php

require_once 'InvitationStatus.php';

class Invitation
{
    public int $id;
    public int $projectId;
    public int $senderId;
    public int $receiverId;
    public InvitationStatus $invitationStatus;
    public string $projectName;

    public function __construct(int $id, int $projectId, int $senderId, int $receiverId, InvitationStatus|string $invitationStatus, string $projectName = '')
    {
        $this->id = $id;
        $this->projectId = $projectId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->projectName = $projectName;

        if (is_string($invitationStatus)) {
            $this->invitationStatus = InvitationStatus::from($invitationStatus);
        } else {
            $this->invitationStatus = $invitationStatus;
        }
    }
}

?>