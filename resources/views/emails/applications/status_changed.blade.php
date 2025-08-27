<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de votre candidature</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .status-update { background: #e8f5e8; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Driv'n Cook</h1>
            <h2>Mise à jour de votre candidature</h2>
        </div>

        <p>Bonjour {{ $application['name'] }},</p>

        <p>Nous vous informons que le statut de votre candidature de franchise a été mis à jour.</p>

        <div class="status-update">
            <strong>Nouveau statut :</strong> {{ __('ui.' . $toStatus) }}<br>
            <strong>Territoire :</strong> {{ $application['territory'] }}
            @if($message)
                <br><strong>Message :</strong> {{ $message }}
            @endif
        </div>

        @if($toStatus === 'approved')
            <p><strong>Félicitations !</strong> Votre candidature a été approuvée. Vous allez être contacté prochainement pour finaliser votre intégration au réseau Driv'n Cook.</p>
        @elseif($toStatus === 'interview')
            <p>Votre candidature progresse bien ! Un entretien va être planifié. Vous serez contacté dans les prochains jours pour convenir d'un rendez-vous.</p>
        @elseif($toStatus === 'prequalified')
            <p>Votre dossier a passé avec succès l'étape de pré-qualification. Nous procédons maintenant à l'examen détaillé de votre candidature.</p>
        @elseif($toStatus === 'rejected')
            <p>Nous vous remercions pour l'intérêt que vous portez à notre enseigne. Malheureusement, nous ne pouvons pas donner suite à votre candidature à ce stade.</p>
        @endif

        <p>Vous pouvez suivre l'avancement de votre candidature sur votre espace personnel.</p>

        <div class="footer">
            <p>
                Cordialement,<br>
                L'équipe Driv'n Cook<br>
                <a href="mailto:contact@drivncook.fr">contact@drivncook.fr</a>
            </p>
        </div>
    </div>
</body>
</html>
