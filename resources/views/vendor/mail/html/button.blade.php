@component('mail::button', ['url' => $url, 'color' => $color ?? 'primary'])
{{ $slot }}
@endcomponent
