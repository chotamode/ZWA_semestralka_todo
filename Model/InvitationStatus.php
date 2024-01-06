<?php

/**
 * InvitationStatus
 * 
 * This is an enum class representing an invitation status.
 * 
 * @category Model
 * @package  Model
 */
enum InvitationStatus: string{
    case PENDING = 'Pending';
    case ACCEPTED = 'Accepted';
    case DECLINED = 'Declined';
}

function stringToInvitationStatus(string $invitationStatus): InvitationStatus {
    switch($invitationStatus) {
        case 'PENDING':
            return InvitationStatus::PENDING;
        case 'ACCEPTED':
            return InvitationStatus::ACCEPTED;
        case 'DECLINED':
            return InvitationStatus::DECLINED;
        default:
            throw new Exception('Invalid invitation status');
    }
}

?>