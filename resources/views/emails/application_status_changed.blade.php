<!doctype html>
<html lang="fr"><meta charset="utf-8">
<body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;">
  <h2>Statut de votre candidature</h2>
  <p><strong>Nouveau statut :</strong> {{ $newStatus }}</p>
  <p><strong>Ancien statut :</strong> {{ $oldStatus }}</p>
  @if(!empty($statusMessage))
    <p><strong>Message :</strong> {{ $statusMessage }}</p>
  @endif
</body>
</html>
