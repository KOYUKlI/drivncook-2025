<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records. Please check your email and password.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Custom messages for better UX
    'account_not_found' => 'No account exists with this email address.',
    'account_inactive' => 'Your account is deactivated. Please contact administration.',
    'invalid_credentials' => 'Invalid email or password. Please check your information.',
    'login_success' => 'Login successful! Welcome.',
    'logout_success' => 'You have been logged out successfully.',
    
    // Password reset messages
    'reset_link_sent' => 'We have sent a password reset link to your email address.',
    'reset_link_failed' => 'Unable to send reset link. Please check your email address.',
    'password_reset_success' => 'Your password has been changed successfully. You can now log in.',
    'password_reset_failed' => 'Password reset failed. The link may be expired or invalid.',
    'token_invalid' => 'This password reset link is invalid or expired.',
    'token_expired' => 'This password reset link has expired. Please request a new one.',
    
    // Franchisee specific messages
    'franchisee_welcome' => 'Welcome to your DrivnCook franchisee dashboard!',
    'franchisee_password_setup' => 'Your password has been set successfully. Welcome to the DrivnCook family!',
    'first_login_notice' => 'First login detected. Don\'t forget to complete your profile.',

];
