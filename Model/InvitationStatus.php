<?php

enum InvitationStatus: string{
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case DECLINED = 'DECLINED';
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