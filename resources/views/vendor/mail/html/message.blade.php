@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. {{ __('emails.all_rights_reserved') }}

{{ __('emails.legal_notice') }}: {{ config('app.name') }}, SAS au capital de 100 000€  
RCS Paris 123 456 789 - Siège social: 123 Rue de l'Innovation, 75001 Paris

{{ __('emails.useful_links') }}: [{{ __('emails.contact_us') }}](mailto:contact@drivncook.com) | [{{ __('emails.privacy_policy') }}]({{ config('app.url') }}/privacy) | [{{ __('emails.terms_of_service') }}]({{ config('app.url') }}/terms)
@endcomponent
@endslot
@endcomponent
