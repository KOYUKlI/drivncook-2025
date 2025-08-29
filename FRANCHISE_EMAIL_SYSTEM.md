# Système d'Envoi d'Emails pour les Candidatures de Franchise

## Vue d'ensemble

Ce document décrit le système d'envoi d'emails intégré au processus de candidature de franchise DrivnCook, avec support complet de l'internationalisation (i18n).

## 🎯 Fonctionnalités Implémentées

### 1. Emails automatiques côté candidat
- **Confirmation de soumission** : Email envoyé immédiatement après soumission de candidature
- **Notifications de changement de statut** : Email à chaque évolution du dossier
- **Email d'onboarding** : Envoi des identifiants de connexion lors de l'approbation

### 2. Emails automatiques côté admin
- **Alerte nouvelle candidature** : Notification immédiate à l'équipe admin
- **Logs détaillés** : Traçabilité complète des envois d'emails

### 3. Support multilingue (i18n)
- **Français** (`lang/fr/emails.php`)
- **Anglais** (`lang/en/emails.php`)
- Templates d'emails adaptés selon la langue de l'utilisateur

## 📧 Types d'Emails

### Pour les Candidats

#### 1. Confirmation de Soumission (`FranchiseApplicationSubmitted`)
- **Déclencheur** : Soumission complète de candidature
- **Destinataire** : Email du candidat
- **Contenu** :
  - Confirmation de réception
  - Récapitulatif de la candidature  
  - Prochaines étapes du processus
  - Lien de suivi de candidature

#### 2. Changement de Statut (`FranchiseApplicationStatusChanged`)
- **Déclencheur** : Mise à jour de statut par l'admin
- **Destinataire** : Email du candidat
- **Contenu** :
  - Nouveau statut
  - Message personnalisé de l'équipe
  - Actions spécifiques selon le statut
  - Conseils de préparation (pour entretien)

#### 3. Onboarding Welcome (`FranchiseeOnboardingWelcome`)
- **Déclencheur** : Approbation de candidature
- **Destinataire** : Email du nouveau franchisé
- **Contenu** :
  - Identifiants de connexion temporaires
  - Instructions de première connexion
  - Prochaines étapes d'onboarding

### Pour les Admins

#### 1. Nouvelle Candidature (`FranchiseApplicationNewAdminAlert`)
- **Déclencheur** : Soumission de nouvelle candidature
- **Destinataire** : Emails admin configurés
- **Contenu** :
  - Détails du candidat
  - Résumé de la candidature
  - Lien direct vers l'interface admin
  - Rappel de délai de traitement

## 🔧 Configuration

### Variables d'environnement (.env)
```env
# Configuration email générale
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=no-reply@drivncook.com
MAIL_FROM_NAME="Driv'n Cook"

# Email admin pour notifications
ADMIN_NOTIFICATION_EMAIL=admin@drivncook.com
```

### Fichier de configuration (config/mail.php)
```php
'admin_notifications' => [
    env('ADMIN_NOTIFICATION_EMAIL', 'admin@drivncook.local'),
    // Possibilité d'ajouter plusieurs emails admin
],
```

## 📂 Structure des Fichiers

### Contrôleurs
- `app/Http/Controllers/Public/FranchiseApplicationController.php` - Gestion candidatures publiques
- `app/Http/Controllers/Admin/ApplicationController.php` - Gestion admin candidatures

### Classes Mail
- `app/Mail/FranchiseApplicationSubmitted.php`
- `app/Mail/FranchiseApplicationNewAdminAlert.php`  
- `app/Mail/FranchiseApplicationStatusChanged.php`
- `app/Mail/FranchiseeOnboardingWelcome.php`

### Templates Email
- `resources/views/emails/applications/submitted.blade.php`
- `resources/views/emails/applications/new-admin-alert.blade.php`
- `resources/views/emails/applications/status-changed.blade.php`
- `resources/views/emails/franchisees/onboarding-welcome.blade.php`

### Traductions
- `lang/fr/emails.php` - Textes français
- `lang/en/emails.php` - Textes anglais
- `lang/fr/ui.php` - Messages interface (ajouts)
- `lang/en/ui.php` - Messages interface (ajouts)

## 🚀 Processus d'Envoi

### 1. Soumission de Candidature
```php
// Dans FranchiseApplicationController@store
Mail::to($app->email)->send(new FranchiseApplicationSubmitted($app));
Mail::send(new FranchiseApplicationNewAdminAlert($app));
```

### 2. Changement de Statut
```php
// Dans Admin/ApplicationController@updateStatus
Mail::to($application->email)->send(
    new FranchiseApplicationStatusChanged($application, $oldStatus, $newStatus, $comment)
);

// Si approbation -> création compte franchisé + email onboarding
if ($newStatus === 'approved') {
    $this->handleApprovedApplication($application);
}
```

### 3. Gestion des Erreurs
- Tous les envois d'emails sont dans des blocs try/catch
- Logs détaillés en cas d'échec
- L'application continue de fonctionner même si les emails échouent

## 💡 Fonctionnalités Avancées

### 1. Création Automatique de Compte Franchisé
Lors de l'approbation d'une candidature :
- Création automatique d'un compte utilisateur
- Génération d'un mot de passe temporaire
- Création d'un enregistrement franchisé
- Attribution du rôle franchisé (si Spatie Permission utilisé)
- Envoi automatique des identifiants

### 2. Templates Responsifs et Attrayants
- Utilisation des composants Markdown de Laravel
- Emojis et mise en forme moderne
- Panels, tableaux et boutons d'action
- Design cohérent avec l'identité visuelle

### 3. Gestion des Statuts
Statuts supportés avec emails spécifiques :
- `submitted` → Confirmation de soumission
- `prequalified` → Message d'encouragement
- `interview` → Conseils de préparation
- `approved` → Félicitations + onboarding
- `rejected` → Message de refus courtois

## 🛠 Personnalisation

### Ajouter de nouveaux statuts
1. Mettre à jour les traductions dans `emails.php`
2. Modifier le template `status-changed.blade.php`
3. Adapter la logique dans `FranchiseApplicationStatusChanged.php`

### Ajouter de nouveaux emails admin
1. Modifier `config/mail.php` :
```php
'admin_notifications' => [
    'admin@drivncook.com',
    'franchise-manager@drivncook.com',
    'operations@drivncook.com',
],
```

### Personnaliser les templates
- Modifier les fichiers `.blade.php` dans `resources/views/emails/`
- Utiliser les composants Markdown Laravel : `@component('mail::button')`, `@component('mail::panel')`, etc.

## 🔍 Tests et Debugging

### Vérifier les logs
```bash
tail -f storage/logs/laravel.log | grep -E "(email|mail)"
```

### Tester l'envoi en local
- Utiliser `MAIL_MAILER=log` en développement
- Vérifier les emails dans `storage/logs/laravel.log`
- Utiliser Mailpit ou MailHog pour prévisualiser

### Variables de debug utiles
```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
MAIL_MAILER=log  # Pour dev
```

## 📋 Checklist de Déploiement

- [ ] Configuration SMTP en production
- [ ] Variables d'environnement configurées
- [ ] Emails admin définis
- [ ] Tests d'envoi effectués
- [ ] Logs de surveillance configurés
- [ ] Templates testés sur différents clients email

---

**Dernière mise à jour** : 29 août 2025  
**Version** : 1.0  
**Auteur** : GitHub Copilot  
