<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Scheduler

This app defines the task scheduler in `routes/console.php` (no Console Kernel). To run scheduled tasks locally with Sail or PHP, use one of:

- Sail: `./vendor/bin/sail artisan schedule:work`
- PHP: `php artisan schedule:work`

Ensure `storage:link` is run once for public files (PDFs, uploads): `php artisan storage:link`.

## Emails

### Configuration

The application uses **Markdown mailables** with custom branded templates. Email configuration:

- **From address/name**: Set in `config/mail.php` (`MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`)
- **Templates**: Located in `resources/views/vendor/mail/` for global branding
- **Email views**: Located in `resources/views/emails/` organized by feature
- **Translations**: `resources/lang/{fr,en}/emails.php` for all subjects and content

### Mailpit Development

For local development, emails are sent to **Mailpit**:
- **Web interface**: http://localhost:8025
- **SMTP**: localhost:1025 (already configured in Sail)
- No real emails are sent in development

### Email Preview (Local Only)

Preview all emails with mock data during development:
- **Index page**: http://localhost/dev/mail/preview
- **Individual preview**: http://localhost/dev/mail/preview/{mailable-name}
- Available only in local environment with authentication

### Queue Configuration

Emails use the configured queue system:
- **Sync mode** (`QUEUE_CONNECTION=sync`): Emails sent immediately
- **Queue mode** (database, redis, etc.): Emails queued for background processing
- The application automatically detects the queue driver and uses `->queue()` or `->send()` accordingly

### Available Mailables

**Franchise Applications:**
- `FranchiseApplicationSubmitted` - Confirmation to applicant
- `FranchiseApplicationStatusChanged` - Status updates (prequalified/interview/approved/rejected)
- `FranchiseeOnboardingWelcome` - Welcome email with credentials after approval
- `FranchiseApplicationNewAdminAlert` - Admin notification for new applications

**Purchase Orders:**
- `PurchaseOrderCreated` - Order confirmation to franchisee and warehouse
- `PurchaseOrderStatusUpdated` - Status change notifications

**Truck Maintenance:**
- `TruckMaintenanceOpened` - Maintenance start notification
- `TruckMaintenanceClosed` - Maintenance completion notification

**Reports:**
- `MonthlySalesReportReady` - Secure PDF download notification

### Customizing Email Templates

1. **Global branding**: Edit files in `resources/views/vendor/mail/html/`
2. **Individual emails**: Edit files in `resources/views/emails/{feature}/`
3. **Translations**: Update `resources/lang/{fr,en}/emails.php`
4. **Colors/styling**: Modify the vendor mail templates (header, footer, button components)

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
