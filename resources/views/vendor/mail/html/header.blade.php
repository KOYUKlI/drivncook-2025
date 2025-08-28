<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<div style="
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    font-size: 24px;
    font-weight: bold;
    color: #ff6b35;
    text-decoration: none;
    display: inline-block;
    padding: 20px;
">
    🚚 {{ $slot }}
</div>
@endif
</a>
</td>
</tr>
