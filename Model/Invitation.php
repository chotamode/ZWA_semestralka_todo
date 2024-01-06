<?php
/**
 * Invitation
 * 
 * This is a model class representing an invitation.
 * 
 * @param int $id Invitation ID that is unique for each invitation in the database (auto-incremented)
 * @param int $projectId Project ID that is unique for each project in the database foreign key to the project table
 * @param int $senderId User ID that is unique for each user in the database foreign key to the users table
 * @param int $receiverId User ID that is unique for each user in the database foreign key to the users table
 * @param InvitationStatus $invitationStatus Invitation status (Pending, Accepted, Declined)
 * @param string $projectName Project name
 * 
 * @category Model
 * @package  Model
 */
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