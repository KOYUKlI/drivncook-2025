@component('mail::message')
# {{ __('emails.hello_name', ['name' => $franchisee->name]) }}

🎉 {{ __('emails.onboarding_welcome_intro') }}

## {{ __('emails.onboarding_account_setup') }}

**Email:** {{ $franchisee->email }}

<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-item">
🔐 **{{ __('emails.onboarding_password_setup') }}**<br>
{{ __('emails.onboarding_password_setup_instruction') }}
</td>
</tr>
</table>
</td>
</tr>
</table>

<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $passwordSetupUrl }}" class="button button-primary" target="_blank" rel="noopener">{{ __('emails.onboarding_setup_password') }}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

## {{ __('emails.onboarding_next_steps') }}

1. {{ __('emails.onboarding_step1_password') }}
2. {{ __('emails.onboarding_step2') }}
3. {{ __('emails.onboarding_step3') }}
4. {{ __('emails.onboarding_step4') }}

{{ __('emails.thanks') }},  
{{ __('emails.signature') }}
@endcomponent
