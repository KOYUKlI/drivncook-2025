@component('mail::message')
# {{ __('emails.hello_name', ['name' => $franchisee->name]) }}

🎉 {{ __('emails.onboarding_welcome_intro') }}

## {{ __('emails.onboarding_credentials') }}

**Email:** {{ $franchisee->email }}  
**Mot de passe temporaire:** `{{ $tempPassword }}`

@component('mail::panel')
⚠️ **Important:** Changez votre mot de passe lors de votre première connexion pour sécuriser votre compte.
@endcomponent

## {{ __('emails.onboarding_next_steps') }}

1. {{ __('emails.onboarding_step1') }}
2. {{ __('emails.onboarding_step2') }}
3. {{ __('emails.onboarding_step3') }}
4. {{ __('emails.onboarding_step4') }}

@component('mail::button', ['url' => $loginUrl])
{{ __('emails.view_in_fo') }}
@endcomponent

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
