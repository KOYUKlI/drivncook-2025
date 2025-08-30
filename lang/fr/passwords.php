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

    'reset' => 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.',
    'sent' => 'Nous avons envoyé un lien de réinitialisation à votre adresse email.',
    'throttled' => 'Veuillez patienter avant de faire une nouvelle demande.',
    'token' => 'Ce lien de réinitialisation de mot de passe est invalide ou a expiré.',
    'user' => 'Aucun compte n\'existe avec cette adresse email.',

    // Custom detailed messages
    'invalid_token' => 'Le lien de réinitialisation est invalide. Vérifiez que vous utilisez le bon lien.',
    'expired_token' => 'Ce lien de réinitialisation a expiré. Les liens sont valables 60 minutes.',
    'already_used_token' => 'Ce lien de réinitialisation a déjà été utilisé.',
    'weak_password' => 'Le mot de passe est trop faible. Utilisez au moins 8 caractères avec majuscules, minuscules et chiffres.',
    'password_same' => 'Le nouveau mot de passe doit être différent de l\'ancien.',
    
    // Success messages with context
    'reset_success_franchisee' => 'Parfait ! Votre mot de passe franchisé a été défini. Bienvenue dans DrivnCook !',
    'reset_success_admin' => 'Mot de passe administrateur modifié avec succès.',
    'reset_success_general' => 'Votre mot de passe a été modifié avec succès.',

];
