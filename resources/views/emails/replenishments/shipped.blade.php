@component('mail::message')
# {{ __('emails.hello_name', ['name' => $order->franchisee->name ?? '']) }}

{{ __('emails.replenishment_shipped_intro') }}

@component('mail::panel')
**{{ __('ui.replenishments.show_title', ['ref' => $order->reference ?? $order->id]) }}**  
{{ __('ui.common.franchisee') }}: {{ $order->franchisee->name ?? '-' }}  
{{ __('ui.common.warehouse') }}: {{ $order->warehouse->name ?? '-' }}  
{{ __('ui.common.created_at') }}: {{ optional($order->created_at)->format('Y-m-d H:i') }}
@endcomponent

@if($downloadUrl)
@component('mail::button', ['url' => $downloadUrl])
{{ __('emails.download_pdf') }}
@endcomponent
@endif

{{ __('emails.thanks') }},<br>
{{ __('emails.signature') }}
@endcomponent
