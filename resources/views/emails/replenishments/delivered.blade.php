@component('mail::message')
# {{ __('emails.hello_name', ['name' => $order->franchisee->name ?? '']) }}

{{ __('emails.replenishment_delivered_intro') }}

@component('mail::panel')
**{{ __('ui.replenishments.show_title', ['ref' => $order->reference ?? $order->id]) }}**  
{{ __('ui.common.franchisee') }}: {{ $order->franchisee->name ?? '-' }}  
{{ __('ui.common.warehouse') }}: {{ $order->warehouse->name ?? '-' }}  
{{ __('ui.common.created_at') }}: {{ optional($order->created_at)->format('Y-m-d H:i') }}
@endcomponent

@component('mail::button', ['url' => route('bo.replenishments.show', $order->id)])
{{ __('emails.view_po') }}
@endcomponent

{{ __('emails.thanks') }},<br>
{{ __('emails.signature') }}
@endcomponent
