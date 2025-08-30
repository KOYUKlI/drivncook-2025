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

    'failed' => 'Ces identifiants ne correspondent à aucun compte. Vérifiez votre email et mot de passe.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    // Custom messages for better UX
    'account_not_found' => 'Aucun compte n\'existe avec cette adresse email.',
    'account_inactive' => 'Votre compte est désactivé. Contactez l\'administration.',
    'invalid_credentials' => 'Email ou mot de passe incorrect. Vérifiez vos informations.',
    'login_success' => 'Connexion réussie ! Bienvenue.',
    'logout_success' => 'Vous avez été déconnecté avec succès.',
    
    // Password reset messages
    'reset_link_sent' => 'Nous avons envoyé un lien de réinitialisation à votre adresse email.',
    'reset_link_failed' => 'Impossible d\'envoyer le lien de réinitialisation. Vérifiez votre adresse email.',
    'password_reset_success' => 'Votre mot de passe a été modifié avec succès. Vous pouvez maintenant vous connecter.',
    'password_reset_failed' => 'Échec de la réinitialisation. Le lien est peut-être expiré ou invalide.',
    'token_invalid' => 'Ce lien de réinitialisation est invalide ou a expiré.',
    'token_expired' => 'Ce lien de réinitialisation a expiré. Demandez un nouveau lien.',
    
    // Franchisee specific messages
    'franchisee_welcome' => 'Bienvenue dans votre espace franchisé DrivnCook !',
    'franchisee_password_setup' => 'Votre mot de passe a été défini avec succès. Bienvenue dans la famille DrivnCook !',
    'first_login_notice' => 'Première connexion détectée. N\'oubliez pas de compléter votre profil.',

];
