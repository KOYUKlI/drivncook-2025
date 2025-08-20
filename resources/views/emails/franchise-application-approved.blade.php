<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Candidature acceptée</title>
    <style>body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;color:#0f172a}</style>
    </head>
<body>
    <h1>Votre candidature a été acceptée</h1>
    <p>Bonjour {{ $application->full_name }},</p>
    <p>Bonne nouvelle ! Votre candidature pour rejoindre la franchise Driv'n Cook a été <strong>acceptée</strong>.</p>
    <p>Un email séparé vous a été envoyé pour <strong>définir votre mot de passe</strong> et accéder à votre espace. Si vous ne le voyez pas, pensez à vérifier vos spams.</p>
    <p>À très vite,<br>L’équipe Driv'n Cook</p>
</body>
</html>
