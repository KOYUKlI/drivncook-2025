@component('mail::message')
# {{ __('emails.hello_name', ['name' => $application->full_name]) }}

🎉 {{ __('emails.application_submitted_intro') }}

{{ __('emails.application_submitted_body') }}

@component('mail::panel')
📋 **Récapitulatif de votre candidature**

**Statut actuel:** {{ __('emails.status_' . $application->status) }}  
**Territoire souhaité:** {{ $application->desired_area ?? 'Non spécifié' }}  
**Date de soumission:** {{ $application->created_at->format('d/m/Y à H:i') }}
@endcomponent

## {{ __('emails.application_submitted_next_steps') }}

1. 📖 {{ __('emails.application_submitted_step1') }}
2. ✅ {{ __('emails.application_submitted_step2') }}
3. 🤝 {{ __('emails.application_submitted_step3') }}

{{ __('emails.application_submitted_footer') }}

**Lien de suivi:** {{ url('/applications/' . $application->id) }}

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}

@component('mail::subcopy')
💡 **Conseils** : Gardez votre téléphone à portée de main, nous vous contacterons prochainement pour discuter de votre projet.
@endcomponent
@endcomponent
