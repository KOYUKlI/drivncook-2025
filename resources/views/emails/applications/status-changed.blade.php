@component('mail::message')
# {{ __('emails.hello_name', ['name' => $application->full_name]) }}

{{ __('emails.application_status_changed_intro') }}

<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-item">
**{{ __('emails.application_current_status') }}:** {{ __('emails.status_' . $newStatus) }}

@if($newStatus === 'prequalified')
✅ {{ __('emails.application_status_prequalified_message') }}
@elseif($newStatus === 'interview')
🤝 {{ __('emails.application_status_interview_message') }}
@elseif($newStatus === 'approved')
🎉 {{ __('emails.application_status_approved_message') }}
@elseif($newStatus === 'rejected')
{{ __('emails.application_status_rejected_message') }}
@endif
</td>
</tr>
</table>
</td>
</tr>
</table>

@if($adminMessage)
## {{ __('emails.application_admin_message') }}
{{ $adminMessage }}
@endif

@if($newStatus === 'approved')
<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-item">
🎉 **{{ __('emails.onboarding_welcome_intro') }}**

Vous recevrez prochainement un email avec vos identifiants de connexion et les prochaines étapes à suivre.

**Prochaines étapes:**
1. 📧 Réception de vos identifiants de connexion
2. 🏠 Accès à votre espace franchisé personnel  
3. 📋 Finalisation de votre profil
4. 🚚 Planification du déploiement de votre camion
</td>
</tr>
</table>
</td>
</tr>
</table>
@elseif($newStatus === 'interview')
<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-item">
**Préparation de l'entretien:**

📋 Préparez-vous à discuter de :
- Votre motivation et vision
- Votre expérience professionnelle
- Votre plan de développement local
- Vos questions sur le concept
</td>
</tr>
</table>
</td>
</tr>
</table>
@endif

**Lien de suivi:** {{ url('/applications/' . $application->id) }}

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
