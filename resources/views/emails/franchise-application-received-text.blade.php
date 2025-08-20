@if($forAdmin)
Nouvelle candidature reçue

Nom: {{ $application->full_name }}
Email: {{ $application->email }}
Ville: {{ $application->city ?: '—' }}
Budget: {{ $application->budget ? number_format($application->budget,0,',',' ') . ' €' : '—' }}

Connectez-vous à l’admin pour consulter les détails et traiter la candidature.
@else
Bonjour {{ $application->full_name }},

Nous avons bien reçu votre candidature pour rejoindre la franchise Driv'n Cook. Elle est en cours d’étude. Nous reviendrons vers vous rapidement.

Récapitulatif:
- Ville souhaitée: {{ $application->city ?: '—' }}
- Budget estimé: {{ $application->budget ? number_format($application->budget,0,',',' ') . ' €' : '—' }}

À très vite,
L’équipe Driv'n Cook
@endif
