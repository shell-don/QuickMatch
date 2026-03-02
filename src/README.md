# Laravel SaaS Starter

Application Laravel 12 ready-to-use avec API REST, Roles/Permissions, Admin Dashboard, API Keys partenaires et sécurité renforcée.

## Stack Technique

### Backend
PHP 8.2+ | Laravel 12.x | Laravel Sanctum 4.x | Spatie Permission 7.x

### Frontend
Tailwind CSS 3.4.x | Alpine.js 3.x | Chart.js 4.x | Vite 5.x

### Outils
PHPUnit 11.x | Laravel Pint 1.x

## Fonctionnalités

### Authentication
Inscription utilisateur (web), Connexion utilisateur (web), Déconnexion, Mot de passe oublié, Réinitialisation mot de passe, Protection CSRF

### API REST (Laravel Sanctum)
Inscription API, Connexion API, Déconnexion API, Profil utilisateur, Tokens persistants, CRUD utilisateurs (admin), Gestion rôles, Permissions, Pagination, Recherche, Filtres, Tri, Rate Limiting 100 req/min

### API Keys Partenaires
Gestion des clés API pour partenaires tiers, Plans paramétrables (Starter, Basic, Pro, Enterprise), Limites utilisateurs et requêtes par plan, Permissions granulaires (users:read, stats:read), Whitelist IPs optionnelle, Régénération et révocation, Tracking usage et statistiques

### Gestion des Rôles (Spatie)
Rôles: admin, user (par défaut), Permissions granulaires, Assignation de rôles aux utilisateurs, Synchronisation rôles/permissions

### Admin Dashboard
Tableau de bord avec statistiques, Graphique Chart.js (inscriptions 12 mois), Gestion utilisateurs (CRUD), Gestion rôles (CRUD), Gestion clés API partenaires, Modification profil

### UI/UX
Design moderne (Tailwind CSS), Composants réutilisables (Blade), Styles Glassmorphism, Alpine.js pour l'interactivité

## Sécurité

### Headers HTTP
Strict-Transport-Security, X-Content-Type-Options, X-Frame-Options, Content-Security-Policy (CSP), Referrer-Policy, Permissions-Policy, Cross-Origin headers (prod)

### Protection
SQL Injection (Eloquent ORM), XSS (Blade escaping + CSP), CSRF (middleware Laravel), Mass Assignment ($fillable), Rate Limiting (login/register), Invalidation tokens après changement mot de passe, TrustedProxies configuré, Logging événements de sécurité

### Production
APP_DEBUG=false, APP_HTTPS=true, SESSION_ENCRYPT=true, CSP strict en production

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=PlanSeeder
npm install
npm run build
```

## Développement

```bash
composer run dev
```

## Tests

```bash
php artisan test
```

107 tests passent, 245 assertions

## Routes

Web: `/`, `/login`, `/register`, `/dashboard`, `/admin/*`
API Auth: `/api/v1/auth/*`
API Admin: `/api/v1/admin/users/*`, `/api/v1/admin/roles/*`
API Partenaires: `/api/v1/partners/users/*`, `/api/v1/partners/stats`
Admin: `/admin/dashboard`, `/admin/users/*`, `/admin/roles/*`, `/admin/api-keys/*`

## Variables d'Environnement

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_HTTPS=false
DB_CONNECTION=sqlite
SESSION_DRIVER=file
SANCTUM_EXPIRATION=60
```

## Licence

MIT
