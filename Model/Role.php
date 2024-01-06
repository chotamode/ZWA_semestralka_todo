<?php

/**
 * UserRole
 * 
 * This is an enum class representing a user role.
 * 
 * @category Model
 * @package  Model
 */

enum UserRole: string {
    case User = 'User';
    case Admin = 'Admin';
    case Owner = 'Owner';
};
?>