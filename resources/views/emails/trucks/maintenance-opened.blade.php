@component('mail::message')
# {{ __('emails.maintenance_opened_intro', ['truck' => $truck->plate]) }}

**{{ __('emails.maintenance_type') }}:** {{ ucfirst($maintenanceLog->kind) }}  
**{{ __('emails.maintenance_date') }}:** {{ $maintenanceLog->started_at->format('d/m/Y à H:i') }}

@component('mail::panel')
⚠️ {{ __('emails.maintenance_truck_unavailable') }}
@endcomponent

@component('mail::button', ['url' => $viewUrl])
{{ __('emails.view_truck') }}
@endcomponent

{{ __('emails.signature') }}
@endcomponent
