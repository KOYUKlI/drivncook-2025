<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'reset' => 'Your password has been reset successfully. You can now log in.',
    'sent' => 'We have sent a password reset link to your email address.',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This password reset link is invalid or expired.',
    'user' => 'No account exists with this email address.',

    // Custom detailed messages
    'invalid_token' => 'The reset link is invalid. Please make sure you are using the correct link.',
    'expired_token' => 'This reset link has expired. Links are valid for 60 minutes.',
    'already_used_token' => 'This reset link has already been used.',
    'weak_password' => 'Password is too weak. Use at least 8 characters with uppercase, lowercase and numbers.',
    'password_same' => 'The new password must be different from the old one.',
    
    // Success messages with context
    'reset_success_franchisee' => 'Perfect! Your franchisee password has been set. Welcome to DrivnCook!',
    'reset_success_admin' => 'Administrator password changed successfully.',
    'reset_success_general' => 'Your password has been changed successfully.',

];
