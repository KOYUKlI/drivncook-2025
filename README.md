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

## Scheduler (Laravel 12)

Task scheduling is defined in `bootstrap/app.php` using `->withSchedule(...)` (no Console Kernel). To run scheduled tasks locally:

- Sail: `./vendor/bin/sail artisan schedule:work`
- PHP: `php artisan schedule:work`

Monthly job: Generate monthly sales PDFs every 1st of the month at 02:00.

After changing scheduling or removing legacy Console Kernel files, run:

```
composer dump-autoload
php artisan optimize:clear
```

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

## Mission 1 demo data (seeders)

This project includes a complete, idempotent Mission 1 dataset. It uses ULIDs for IDs, money in cents, UTC timestamps, and Spatie roles/permissions.

- Warehouses: WH-PAR, WH-NE, WH-SUD, WH-OUEST
- Users/Roles: admin, warehouse, fleet, franchisee
- Franchisees, trucks, deployments, maintenance, replenishment orders, sales, and monthly report indices

How to recreate the database and seed:

```
php artisan migrate:fresh --seed
```

Demo logins (password: `password`):

- admin@drivncook.test (admin)
- warehouse@drivncook.test (warehouse)
- fleet@drivncook.test (fleet)
- fr1@dc.test, fr2@dc.test, fr3@dc.test (franchisee)

Notes:

- Seeders are idempotent; you can run seeding multiple times safely.
- Legacy seeders are deprecated and no-op; DatabaseSeeder orchestrates the current flow.

## Testing the FO (Franchisee Portal)

The project includes a dedicated demo account for testing the Franchisee Portal (FO) features:

```
URL: http://localhost/fo/dashboard
Email: demo@drivncook.test
Password: demodemo
```

This demo account includes:
- Pre-configured franchisee with complete profile
- Assigned truck with details
- Recent sales data for testing reports and statistics
- Monthly sales reports from previous months

### Requirements for proper FO testing:

1. Create the storage symlink (required for PDF reports and uploads):
   ```
   php artisan storage:link
   ```

2. Run the scheduler for automatic report generation (in a separate terminal):
   ```
   php artisan schedule:work
   ```

3. Make sure to clear optimization cache after any major changes:
   ```
   php artisan optimize:clear
   ```

4. To verify FO routes are correctly registered:
   ```
   php artisan route:list --path=fo
   ```

### Available FO Features (Laravel 12.26.2)

The FO interface provides franchisees with these core features:

| Feature | Route | Description |
|---------|-------|-------------|
| Dashboard | `/fo/dashboard` | Overview with key metrics |
| My Truck | `/fo/truck` | Truck details, maintenance requests |
| Sales | `/fo/sales` | List and create sales transactions |
| Reports | `/fo/reports` | Access monthly reports |
| Account | `/fo/account` | User profile and preferences |

The dedicated `FODemoSeeder` populates data for all these features to enable immediate testing without manual data entry.
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
