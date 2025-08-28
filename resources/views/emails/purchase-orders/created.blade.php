@component('mail::message')
# {{ __('emails.po_created_intro') }}

## {{ __('emails.po_order_summary') }}

**{{ __('emails.po_order_number') }}:** {{ $purchaseOrder->number }}  
**{{ __('emails.po_order_date') }}:** {{ $purchaseOrder->created_at->format('d/m/Y') }}  
**{{ __('emails.po_order_total') }}:** {{ number_format($purchaseOrder->total_cents / 100, 2) }}€

@component('mail::table')
| Article | Quantité | Prix unitaire | Total |
| :------ | :------- | :------------ | ----: |
@foreach($purchaseOrder->lines as $line)
| {{ $line->stockItem->name }} | {{ $line->quantity }} | {{ number_format($line->unit_price_cents / 100, 2) }}€ | {{ number_format(($line->quantity * $line->unit_price_cents) / 100, 2) }}€ |
@endforeach
@endcomponent

@component('mail::button', ['url' => $viewUrl])
{{ __('emails.view_po') }}
@endcomponent

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
