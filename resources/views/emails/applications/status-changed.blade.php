@component('mail::message')
# {{ __('emails.hello_name', ['name' => $application->full_name]) }}

{{ __('emails.application_status_changed_intro') }}

**{{ __('emails.application_current_status') }}:** {{ __('emails.status_' . $newStatus) }}

@if($adminMessage)
## {{ __('emails.application_admin_message') }}
{{ $adminMessage }}
@endif

@if($newStatus === 'approved')
@component('mail::panel')
🎉 **{{ __('emails.onboarding_welcome_intro') }}**

Vous recevrez prochainement un email avec vos identifiants de connexion et les prochaines étapes à suivre.
@endcomponent
@endif

@component('mail::button', ['url' => route('fo.dashboard')])
{{ __('emails.view_in_fo') }}
@endcomponent

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
