<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $forAdmin ? 'Nouvelle candidature franchise' : 'Candidature bien reçue' }}</title>
    <style>body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;color:#0f172a} .muted{color:#475569}</style>
    </head>
<body>
    @if($forAdmin)
        <h1>Nouvelle candidature franchise</h1>
        <p>Une nouvelle candidature a été soumise.</p>
        <ul>
            <li><strong>Nom:</strong> {{ $application->full_name }}</li>
            <li><strong>Email:</strong> {{ $application->email }}</li>
            <li><strong>Ville:</strong> {{ $application->city ?: '—' }}</li>
            <li><strong>Budget:</strong> {{ $application->budget ? number_format($application->budget,0,',',' ') . ' €' : '—' }}</li>
        </ul>
        <p class="muted">Connectez-vous à l’admin pour consulter les détails et traiter la candidature.</p>
    @else
        <h1>Nous avons bien reçu votre candidature</h1>
        <p>Bonjour {{ $application->full_name }},</p>
        <p>Merci pour votre intérêt pour la franchise Driv'n Cook. Votre candidature est maintenant <strong>en cours d’étude</strong>. Nous reviendrons vers vous rapidement.</p>
        <p class="muted">Récapitulatif:</p>
        <ul>
            <li><strong>Ville souhaitée:</strong> {{ $application->city ?: '—' }}</li>
            <li><strong>Budget estimé:</strong> {{ $application->budget ? number_format($application->budget,0,',',' ') . ' €' : '—' }}</li>
        </ul>
    @endif
</body>
</html>
