@component('mail::message')
# {{ __('emails.new_application_admin') }}

{{ __('emails.admin_new_application_intro') }}

## {{ __('emails.admin_application_details') }}

**{{ __('emails.admin_candidate_name') }}:** {{ $application->full_name }}  
**{{ __('emails.admin_candidate_email') }}:** {{ $application->email }}  
**{{ __('emails.admin_candidate_territory') }}:** {{ $application->desired_area ?? 'Non spécifié' }}  
**Date de soumission:** {{ $application->created_at->format('d/m/Y à H:i') }}

@component('mail::button', ['url' => $boUrl])
{{ __('emails.view_application') }}
@endcomponent

{{ __('emails.signature') }}
@endcomponent
