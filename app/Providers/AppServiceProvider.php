<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Observers\PaymentObserver;
use App\Observers\OrderItemObserver;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    Payment::observe(PaymentObserver::class);
    OrderItem::observe(OrderItemObserver::class);

        // Customize password reset email content (approval onboarding)
        ResetPasswordNotification::toMailUsing(function ($notifiable, string $url) {
            return (new MailMessage)
                ->subject("Activez votre accès — définissez votre mot de passe")
                ->greeting('Bienvenue chez Driv\'n Cook')
                ->line("Bonjour {$notifiable->name},")
                ->line("Votre candidature a été acceptée et votre compte franchise a été créé.")
                ->line('Pour accéder à votre espace, définissez votre mot de passe:')
                ->action('Définir mon mot de passe', $url)
                ->line('Ce lien expire dans 60 minutes.')
                ->salutation("L’équipe Driv'n Cook");
        });

        // Force the reset URL to use APP_URL (including port) to avoid wrong host in mails
        ResetPasswordNotification::createUrlUsing(function ($notifiable, string $token) {
            $base = rtrim((string) config('app.url'), '/');
            $path = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false); // relative path

            return $base.$path;
        });
    }
}
