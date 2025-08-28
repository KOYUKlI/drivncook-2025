@component('mail::message')
# {{ __('emails.hello_name', ['name' => $application->full_name]) }}

{{ __('emails.application_submitted_intro') }}

{{ __('emails.application_submitted_body') }}

## {{ __('emails.application_submitted_next_steps') }}

- {{ __('emails.application_submitted_step1') }}
- {{ __('emails.application_submitted_step2') }}
- {{ __('emails.application_submitted_step3') }}

**{{ __('emails.application_current_status') }}:** {{ __('emails.status_' . $application->status) }}  
**{{ __('emails.admin_candidate_territory') }}:** {{ $application->desired_area ?? 'Non spécifié' }}

@component('mail::button', ['url' => route('fo.dashboard')])
{{ __('emails.view_in_fo') }}
@endcomponent

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
