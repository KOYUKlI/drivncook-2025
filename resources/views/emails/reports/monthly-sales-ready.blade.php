@component('mail::message')
# {{ __('emails.hello_name', ['name' => $franchisee->name]) }}

{{ __('emails.monthly_report_intro') }}

**{{ __('emails.report_period') }}:** {{ $period }}

@component('mail::panel')
🔐 {{ __('emails.report_secure_link') }}
@endcomponent

@component('mail::button', ['url' => $downloadUrl])
{{ __('emails.download_pdf') }}
@endcomponent

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
