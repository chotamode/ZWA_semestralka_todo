<?php

/**
 * notification.php
 * 
 * This file contains a function that renders a notification with a given message, so that we can display it to the user.
 * 
 * @category Blocks
 * @package  Blocks
 */

/**
 * renderNotification
 * 
 * This function renders a notification with a given message, so that we can display it to the user.
 * 
 * @param string $message Message to be displayed
 * 
 */

function renderNotification($message)
{
    echo '<div class="notification">';
    if (isset($message)) {
        echo '<p>' . messageToText($message) . '</p>';
    }
    echo '</div>';
}

/**
 * messageToText
 * 
 * This function converts a message to a text that can be displayed to the user.
 * 
 * @param string $message Message to be converted
 * 
 * @return string Converted message
 * 
 */
function messageToText($message)
{
    switch ($message) {
        case 'password_changed':
            return 'Password changed';
        case 'old_password_incorrect':
            return 'Old password is incorrect';
        case 'passwords_do_not_match':
            return 'Passwords do not match';
        case 'incorrect_login_data':
            return 'Wrong username or password';
        case 'username_taken':
            return 'Username is already taken';
        case 'email_taken':
            return 'Email is already taken';
        case 'email_invalid':
            return 'Email should be in format email@domain.domain';
        case 'user_updated':
            return 'User updated';
        case 'user_deleted':
            return 'User deleted';
        case 'name_too_short':
            return 'Name should be at least 3 characters long';
        case 'surname_too_short':
            return 'Surname should be at least 3 characters long';
        case 'username_too_short':
            return 'Username should be at least 3 characters long';
        case 'password_too_short':
            return 'Password should be at least 3 characters long';
        case 'password_confirm_too_short':
            return 'Password confirm should be at least 3 characters long';
        case 'name_too_long':
            return 'Name should be at most 50 characters long';
        case 'surname_too_long':
            return 'Surname should be at most 50 characters long';
        case 'username_too_long':
            return 'Username should be at most 50 characters long';
        case 'password_too_long':
            return 'Password should be at most 50 characters long';
        case 'password_confirm_too_long':
            return 'Password confirm should be at most 50 characters long';
        case 'name_invalid':
            return 'Name should contain only letters';
        case 'surname_invalid':
            return 'Surname should contain only letters';
        case 'username_invalid':
            return 'Username should contain only letters and numbers';
        case 'password_invalid':
            return 'Password should contain only letters and numbers';
        case 'password_confirm_invalid':
            return 'Password confirm should contain only letters and numbers';
        case 'user_created':
            return 'User created';
        case 'user_already_assigned':
            return 'User is already assigned to this project';
        case 'user_already_invited':
            return 'User is already invited to this project';
        case 'user_not_found':
            return 'User not found';
        case 'invitation_sent':
            return 'Invitation sent';
        case 'project_created':
            return 'Project created';
        case 'project_updated':
            return 'Project updated';
        case 'project_deleted':
            return 'Project deleted';
        case 'not_owner':
            return 'You are not the owner of this project';
        default:
            return $message;
    }
}
