@component('mail::message')
# {{ __('emails.maintenance_closed_intro', ['truck' => $truck->plate]) }}

**{{ __('emails.maintenance_type') }}:** {{ ucfirst($maintenanceLog->kind) }}  
**{{ __('emails.maintenance_completed_date') }}:** {{ $maintenanceLog->closed_at->format('d/m/Y à H:i') }}

@if($maintenanceLog->completed_work)
## {{ __('emails.maintenance_work_done') }}
{{ $maintenanceLog->completed_work }}
@endif

@component('mail::panel')
✅ {{ __('emails.maintenance_truck_available') }}
@endcomponent

@component('mail::button', ['url' => $viewUrl])
{{ __('emails.view_truck') }}
@endcomponent

{{ __('emails.signature') }}
@endcomponent
