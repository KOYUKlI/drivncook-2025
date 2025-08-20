<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Activez votre accès</title>
  <style>body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;color:#0f172a}</style>
</head>
<body>
  <h1>Bienvenue chez Driv'n Cook</h1>
  <p>Bonjour {{ $user->name }},</p>
  <p>Votre candidature a été acceptée et votre compte franchise a été créé.</p>
  <p>Pour accéder à votre espace, définissez votre mot de passe en suivant ce lien:</p>
  <p><a href="{{ $resetUrl }}" style="background:#0ea5e9;color:white;padding:10px 16px;border-radius:6px;text-decoration:none;display:inline-block">Définir mon mot de passe</a></p>
  <p style="color:#475569">Ce lien expire dans 60 minutes.</p>
  <p>À tout de suite,<br>L’équipe Driv'n Cook</p>
</body>
</html>
