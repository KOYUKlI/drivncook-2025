@component('mail::message')
# {{ __('emails.po_status_updated_intro') }}

**{{ __('emails.po_order_number') }}:** {{ $purchaseOrder->number }}  
**{{ __('emails.po_new_status') }}:** {{ __('emails.po_status_' . strtolower($newStatus)) }}

@if($note)
## {{ __('emails.po_status_note') }}
{{ $note }}
@endif

@component('mail::button', ['url' => $viewUrl])
{{ __('emails.view_po') }}
@endcomponent

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
