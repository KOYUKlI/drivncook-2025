@component('mail::message')
# 🚨 {{ __('emails.new_application_admin') }}

{{ __('emails.admin_new_application_intro') }}

@component('mail::panel')
## {{ __('emails.admin_application_details') }}

**{{ __('emails.admin_candidate_name') }}:** {{ $application->full_name }}  
**{{ __('emails.admin_candidate_email') }}:** {{ $application->email }}  
@if($application->phone)
**Téléphone:** {{ $application->phone }}  
@endif
**{{ __('emails.admin_candidate_territory') }}:** {{ $application->desired_area ?? 'Non spécifié' }}  
**Date de soumission:** {{ $application->created_at->format('d/m/Y à H:i') }}  
**ID candidature:** `{{ $application->id }}`
@endcomponent

@component('mail::table')
| Statut | Documents joints | Actions requises |
|:-------------|:------------|:-----------|
| {{ __('emails.status_' . $application->status) }} | @if($application->documents->count() > 0) {{ $application->documents->count() }} document(s) @else Aucun @endif | Révision et traitement |
@endcomponent

**Lien admin:** {{ $boUrl }}

{{ __('emails.signature') }}

@component('mail::subcopy')
⏰ **Rappel** : Les candidatures doivent être traitées dans un délai de 48h pour maintenir un excellent service candidat.
@endcomponent
@endcomponent
