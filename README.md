<h1 align="center">Driv’n Cook — Mission 1 · Gestion des services franchisés</h1>

Ce dépôt contient l’implémentation de la Mission 1 du projet Driv’n Cook : une application web PHP/JS permettant de gérer un réseau de franchisés (back‑office d’administration et espace franchisé), le parc de camions, les entrepôts, les approvisionnements, l’inventaire et les ventes, avec export PDF.

## Présentation du projet

Driv’n Cook est un réseau de food‑trucks organisés en franchises. La Mission 1 consiste à mettre en place :

- un back‑office d’administration pour gérer les franchisés, camions, entrepôts, catalogues d’approvisionnement, inventaires et consultations des ventes ;
- un espace « franchisé » pour suivre son parc (camions), passer des commandes de stock et gérer la maintenance ; visualiser ses ventes et demander un camion supplémentaire ;
- une API sécurisée (Sanctum) pour automatiser certaines opérations ;
- l’export des ventes en PDF (rapports administratifs) ;
- une base technique prête au déploiement (réécriture d’URL, configuration d’erreurs, Docker Sail en option).

## Fonctionnalités développées

- Gestion des franchisés
	- CRUD complet des franchisees (`Admin\FranchiseeController`) : création/édition/suppression, consultation détaillée.
	- Attacher/détacher des utilisateurs existants à une franchise (rôle « franchise »).
	- Suivi des candidatures de franchisés (public : `GET/POST /franchise/apply`, admin : revue/approbation/rejet).

- Parc de camions et déploiements
	- CRUD camions côté admin uniquement (affectation à une franchise).
	- Côté franchisé : consultation et édition limitée, pas d’auto‑ajout ni suppression.
	- Workflow « Demander un camion » : création par le franchisé, revue/approbation par l’admin (avec option de création d’un camion lors de l’approbation).
	- Déploiements et localisations (`Admin\TruckDeploymentController`, `Admin\LocationController`).
	- Gestion de la maintenance côté franchisé (`Franchise\MaintenanceRecordController`).

- Entrepôts et approvisionnements (séparés des ventes)
	- CRUD entrepôts et catalogue des fournitures/ingrédients (`Admin\WarehouseController`, `Admin\SupplyController`, `Admin\SupplierController`).
	- Bons de commande de stock (franchisé et admin) + items, finalisation d’une commande.
	- Réception de stock en inventaire lors de la complétion d’un bon (`App\Services\InventoryService::receiveStockOrder`).

- Inventaire (multi‑entrepôts)
	- Consultation filtrable, lots et mouvements, ajustements et transferts (`Admin\InventoryController`, `InventoryLotController`).
	- Traçabilité des mouvements (in/out) et ajustements.

- Ventes et paiements (séparés des approvisionnements)
	- Modélisation des ventes client (`CustomerOrder`, `OrderItem`) et paiements (`Payment`).
	- Back‑office de consultation des ventes (`Admin\SalesController` : index/show).
	- (Mission 2 désactivée) Aucune fidélité/carte de fidélité.

- Génération PDF
	- Export PDF des ventes (dernières 200) via Dompdf : `GET /admin/exports/sales.pdf` (inline) ou `?download=1` (téléchargement). Un lien d’export est accessible depuis l’espace franchisé.

- Conformité 80/20 (chiffre d’affaires)
	- Écran de contrôle mensuel admin (`Admin\ComplianceController`) avec saisie d’un CA externe et calcul du ratio officiel/externe.

- API REST (Sanctum)
	- Endpoints `routes/api.php` pour admin (supplies, suppliers, warehouses, trucks, deployments, locations, stock‑orders, inventaire) et franchisé (stock‑orders, items).

## Architecture technique

- Framework : Laravel 12 (PHP ≥ 8.2), Eloquent (les modèles tiennent lieu de DAO).
- Front : Blade + Tailwind CSS + Alpine.js, bundlé avec Vite.
- Back‑office et espace franchisé :
	- Menus et mises en page dans `resources/views/layouts/app.blade.php`.
	- Écrans admin sous `resources/views/admin/*`, écrans franchisé sous `resources/views/franchise/*`.
	- Séparation claire des modules : Ventes ≠ Approvisionnement, Admin ≠ Franchisé.
- Services applicatifs : `App\Services\InventoryService`, `DeploymentService`, etc.
- Sécurité et rôles : middlewares `AdminMiddleware`, `FranchiseMiddleware`, `EnsureFranchiseAttached`.
- API : routes sanctuarisées par `auth:sanctum` (voir `routes/api.php`).
- Génération PDF : `App\Http\Controllers\Admin\ExportController` + template `resources/views/admin/sales/pdf.blade.php` (accès admin + lien depuis l’espace franchisé).

## Nouvelles entités

- `TruckRequest` : demandes de camion supplémentaire (pending/approved/rejected), initiées par le franchisé, traitées par l’admin.
- `FranchiseApplication` : candidature publique avec acceptations explicites :
	- frais d’entrée 50 000 € (`accept_entry_fee`)
	- redevance 4 % du chiffre d’affaires (`accept_royalty`)
	L’admin peut approuver (création franchise + compte utilisateur franchisé + invitation par e‑mail) ou rejeter.
- Données & migrations : schéma versionné dans `database/migrations` (franchises, trucks, warehouses, supplies, stock orders, inventory, dishes/ingredients, orders/paiements, compliance KPIs…).
- Docker (optionnel) : Laravel Sail (`docker-compose.yml`) avec MySQL 8 et Mailpit.
- Réécriture d’URL : `public/.htaccess` (Apache), configuration Nginx équivalente fournie ci‑dessous.

## Pré‑requis

- PHP 8.2+ avec extensions : `pdo_mysql`, `mbstring`, `openssl`, `json`, `ctype`, `tokenizer`, `xml`, `curl`. Pour Dompdf : `gd` (ou `imagick`, optionnel).
- Composer 2.x
- Base de données : MySQL 8.x (ou MariaDB compatible) — SQLite supportée en dev.
- Node.js 18+ et npm (ou pnpm) pour le front (Vite/Tailwind/Alpine).
- Optionnel : Docker + Docker Compose (Laravel Sail) pour un setup rapide.

## Installation

1) Cloner le dépôt et installer les dépendances

```bash
git clone https://github.com/KOYUKlI/drivncook-2025.git
cd drivncook-2025
composer install
npm ci
```

2) Configuration de l’environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditer `.env` pour pointer votre BDD :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=drivncook
DB_USERNAME=your_user
DB_PASSWORD=your_password
APP_URL=http://localhost
```

3) Migration et jeux de données

```bash
php artisan migrate
# Profil de seed : minimal (défaut) | demo | bulk | real
SEED_PROFILE=demo php artisan db:seed
```

Comptes de démonstration (profil `minimal`/`demo`) :

- Admin : `admin@local.test` / `password`
- Franchisé : `franchise@local.test` / `password`

4) Démarrage en développement

Deux options :

- Local (PHP) :

```bash
npm run dev   # lance Vite
php artisan serve
```

- Docker (Laravel Sail) :

```bash
php artisan sail:install --with=mysql,mailpit
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --force
SEED_PROFILE=demo ./vendor/bin/sail artisan db:seed
./vendor/bin/sail npm run dev
```

5) Document root (prod) : pointer le serveur web sur `public/` (cf. réécriture d’URL ci‑dessous).

## Configuration

- Environnements : `APP_ENV`, `APP_DEBUG=false` en production, `APP_URL` correct (nécessaire à Sanctum).
- Base de données : `config/database.php` supporte MySQL/MariaDB/SQLite/PGSQL.
- Auth/API : Laravel Sanctum. Si hébergé sur domaine différent du front, configurer `SANCTUM_STATEFUL_DOMAINS` et `SESSION_DOMAIN`.
- Mail : Mailpit en dev via Sail (UI sur `:8025`).
- Dompdf : activé avec `Options::set('isRemoteEnabled', true)` pour les assets externes. Assurez‑vous que `allow_url_fopen` et les polices nécessaires sont disponibles.
- Droits d’accès : autoriser l’écriture sur `storage/` et `bootstrap/cache/`.

Réécriture d’URL – Apache (`public/.htaccess` inclus) : mod_rewrite requis.

Nginx (exemple) :

```
server {
		server_name drivncook.local;
		root /var/www/drivncook/public;
		index index.php;

		location / {
				try_files $uri $uri/ /index.php?$query_string;
		}

		location ~ \.php$ {
				include fastcgi_params;
				fastcgi_pass unix:/run/php/php8.2-fpm.sock;
				fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		}
}
```

## Utilisation

- Connexion : `/login`.
	- Les admins sont redirigés vers le tableau de bord admin (`/admin/dashboard`).
	- Les franchisés vers leur tableau de bord (`/franchise/dashboard`). Un franchisé non rattaché est invité à contacter l’admin.

- Back‑office (admin)
	- Franchisees : créer/éditer/supprimer, attacher/détacher des utilisateurs.
	- Trucks / Warehouses / Supplies / Suppliers : gestion du parc et du catalogue.
	- Inventory : consultation, ajustements (`POST /admin/inventory/adjust`) et transferts (`POST /admin/inventory/move`).
	- Dishes & Ingredients : base pour la BOM (recettes/consommations).
	- Sales : liste et détail des ventes.
	- Compliance 80/20 : saisie de CA externe par franchise et suivi du ratio.
	- Commissions 4 % : écran de synthèse mensuelle et mise à jour d’état (pending/paid/canceled). Calcul via `App\Services\CommissionService` et commande artisan.
	- Exports : PDF des ventes (voir section dédiée).

- Espace franchisé
	- Trucks : gestion de ses camions.
	- Stock Orders : créer des bons de commande, ajouter/supprimer des items, marquer « complete » pour déclencher la réception d’inventaire côté entrepôt.
	- Maintenance : journaliser les opérations de maintenance.

- API (extraits)
	- Admin (via `auth:sanctum`) : `GET /api/admin/inventory`, `POST /api/admin/inventory/{id}/adjust`, `apiResource` supplies/suppliers/warehouses/trucks/deployments/locations/stock-orders…
	- Franchise : `apiResource /api/franchise/stock-orders`, `POST /api/franchise/stock-orders/{id}/items`.

## Génération de factures et rapports PDF

- Rapport des ventes (dernières 200) : `GET /admin/exports/sales.pdf`
	- Paramètre `?download=1` pour forcer le téléchargement.
	- Template modifiable : `resources/views/admin/sales/pdf.blade.php`.
	- Moteur : Dompdf 3.x (via `dompdf/dompdf`). Papier A4 portrait, assets distants autorisés.

Astuce : pour personnaliser la mise en page (logo, styles), éditez la vue Blade dédiée et relancez l’export.

## Inventaire des vues & menus (Mission 1)

| Chemin | Rôle | Statut | Raison | Actions |
|---|---|---|---|---|
| `admin/dashboard` | Admin | Keep | Accueil back‑office | — |
| `admin/trucks/*` | Admin | Keep | Parc camions (admin only) | — |
| `admin/truckrequests/*` | Admin | Keep | Workflow demandes camions | — |
| `admin/warehouses/*` | Admin | Keep | Entrepôts | — |
| `admin/supplies/*` | Admin | Keep | Catalogue approvisionnement | — |
| `admin/inventory*` | Admin | Keep | Inventaire / lots / mouvements | — |
| `admin/suppliers/*` | Admin | Keep | Fournisseurs | — |
| `admin/locations/*` | Admin | Keep | Emplacements | — |
| `admin/deployments/*` | Admin | Keep | Déploiement camions | — |
| `admin/compliance*` | Admin | Keep | Ratio 80/20 | — |
| `admin/sales*` | Admin | Keep | Ventes (consultation) | — |
| `admin/exports/sales.pdf` | Admin | Keep | Export PDF ventes | — |
| `admin/commissions*` | Admin | Keep | Redevance 4% | — |
| `admin/franchisees*` | Admin | Keep | Gestion franchisés | — |
| `admin/franchise-applications*` | Admin | Keep | Revue candidatures | — |
| `admin/dishes*` | Admin | Remove | Mission 2 | Routes masquées + menu retiré |
| `franchise/dashboard` | Franchise | Keep | Accueil espace franchisé | — |
| `franchise/trucks*` | Franchise | Keep | Mes camions (création bloquée) | — |
| `franchise/truckrequests*` | Franchise | Keep | Demandes camions | — |
| `franchise/stockorders*` | Franchise | Keep | Approvisionnement | — |
| `franchise/sales*` | Franchise | Keep | CA + historiques + export | — |
| `franchise/maintenance*` | Franchise | Rename | Truck Maintenance (pannes & carnet d’entretien) | Texte UI |
| `franchise/apply` | Public | Keep | Candidature franchisé (sans budget) | Acceptations + Stripe après validation |

## Stripe (test) — Droit d’entrée

Variables `.env` à ajouter :

```
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
```

Flux :
- Validation admin → création d’une session Stripe Checkout (50 000 €) → redirection du candidat.
- Webhook `checkout.session.completed` → `entry_fee_status=paid`, `entry_fee_paid_at=now()` → invitation compte.
- Annulation/erreur : page de retour avec message et possibilité de relancer.

Routes prévues :
- `POST /franchise-applications/{id}/checkout` (admin) — crée la session et renvoie l’URL.
- `POST /stripe/webhook` — traite l’évènement et met à jour l’application.

Sécurité : signature webhook vérifiée via `STRIPE_WEBHOOK_SECRET`.

## Modules Mission 2 désactivés (hors scope)

Les fonctionnalités suivantes sont volontairement retirées pour la Mission 1 :

- Fidélité (règles, cartes, transactions)
- Newsletters (opt‑in, création/envoi)
- Événements et inscriptions
- Multi‑langue des plats (Dish translations)

Les routes correspondantes sont supprimées et le code résiduel n’est plus utilisé. Les tests associés sont marqués « skipped ».

## Points d’amélioration et extensions possibles

- Contrôle 80/20 du stock (extension) : aujourd’hui, l’écran « Compliance » mesure un ratio CA officiel/externe. À étendre côté inventaire/recettes pour comparer consommations issues des BOM vs achats (mix d’achats) et alerter sous les 80 %.
- Facturation détaillée par commande : génération de facture PDF unitaire avec numérotation, TVA, mentions légales.
- Règles de commissionnement automatiques et vues de synthèse par période.
- Compléter la consommation d’inventaire à partir des recettes (service `InventoryService::consume`) lors de la validation d’une vente.
- Notifications (mail) : candidatures franchisé, seuils d’inventaire, envoi de newsletters.
- Observabilité et fiabilité : tests unitaires/feature (Pest), CI, écrans d’erreurs personnalisés.

---

### Annexes

Jeux de données (seed)

L’application expose un seeder consolidé `BaselineSeeder` (appelé par `DatabaseSeeder`) piloté par la variable d’environnement `SEED_PROFILE` :

- `minimal` (défaut) : admin & franchisé, 1 franchise, 2 entrepôts, fournitures cœur, règle de fidélité.
	(Note : la fidélité est désactivée en Mission 1.)
- `demo` : minimal + plat « Burger Demo », 1 camion, bon de commande reçu, 1 vente client.
- `bulk` : demo + davantage de fournitures (≈60) et camions.
- `real` : dataset multi‑franchises réaliste (entrepôts, camions, plats, commandes, ventes).

Exemples :

```bash
php artisan db:seed                   # minimal
SEED_PROFILE=demo php artisan db:seed # démo
SEED_PROFILE=bulk php artisan db:seed # volumétrie
SEED_PROFILE=real php artisan db:seed # dataset réaliste
```

Déploiement

- Pointez le vhost sur `public/` et activez la réécriture. Désactivez `APP_DEBUG` en prod, configurez les logs (`config/logging.php`).
- Pensez aux tâches planifiées/queues si vous ajoutez des traitements asynchrones (voir Laravel Scheduler/Queue).
